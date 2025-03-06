<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\User;
use App\Models\CaseCollaborator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\CollaboratorInvited;

class CaseCollaboratorController extends Controller
{
    public function index(CaseFile $caseFile): JsonResponse
    {
        $this->authorize('view', $caseFile);

        $collaborators = $caseFile->collaborators()
            ->with('user:id,name,email')
            ->get();

        return response()->json($collaborators);
    }

    public function store(Request $request, CaseFile $caseFile): JsonResponse
    {
        $this->authorize('manageCollaborators', $caseFile);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:viewer,editor,manager'
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Prevent duplicate collaborators
        if ($caseFile->hasCollaborator($user)) {
            return response()->json([
                'message' => 'User is already a collaborator'
            ], 422);
        }

        $collaborator = $caseFile->collaborators()->create([
            'user_id' => $user->id,
            'role' => $validated['role'],
            'status' => 'pending'
        ]);

        CollaboratorInvited::dispatch($collaborator);

        return response()->json($collaborator, 201);
    }

    public function update(
        Request $request,
        CaseFile $caseFile,
        CaseCollaborator $collaborator
    ): JsonResponse {
        $this->authorize('manageCollaborators', $caseFile);

        $validated = $request->validate([
            'role' => 'required|in:viewer,editor,manager',
            'status' => 'required|in:active,revoked'
        ]);

        $collaborator->update($validated);

        return response()->json($collaborator);
    }

    public function destroy(
        CaseFile $caseFile,
        CaseCollaborator $collaborator
    ): JsonResponse {
        $this->authorize('manageCollaborators', $caseFile);

        $collaborator->delete();

        return response()->json(null, 204);
    }
}