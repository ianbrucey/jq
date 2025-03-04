### _(note*** this is a guide. It is not law and is subject to change)_

### Feature Name & Structure
**1. Feature Name:** **Correspondence Tracker**  
**Dashboard Name:** `Correspondence Dashboard` (clear and specific)  
**Workflow:**
- **Threads** represent grouped communications (e.g., "Dispute with Experian").
- **Communications** are individual interactions (emails, letters, calls) within a thread.

---

### Database Tables & Relationships
**Tables:**
1. **`threads`**
    - `id`
    - `case_file_id` (foreign key to `case_files`)
    - `title` (e.g., "Insurance Claim Follow-Up")
    - `status` (enum: `open`, `closed`, `archived`)
    - `created_by` (user who started the thread)
    - `created_at`, `updated_at`

2. **`communications`**
    - `id`
    - `thread_id` (foreign key to `threads`)
    - `type` (enum: `email`, `letter`, `phone`, `other`)
    - `content` (text for pasted content; nullable if using documents)
    - `sent_at` (datetime of the communication)
    - `subject` (optional, e.g., "Re: Settlement Offer")
    - `created_at`, `updated_at`

3. **`communication_participants`** (pivot table for senders/recipients)
    - `id`
    - `communication_id`
    - `party_id` (foreign key to `parties` from the address book)
    - `role` (enum: `sender`, `recipient`)

4. **`communication_documents`** (pivot table for document attachments)
    - `id`
    - `communication_id`
    - `document_id` (foreign key to `documents`)

---

### Model Relationships (Laravel)
```php  
// Thread.php  
public function caseFile() { return $this->belongsTo(CaseFile::class); }  
public function communications() { return $this->hasMany(Communication::class); }  

// Communication.php  
public function thread() { return $this->belongsTo(Thread::class); }  
public function participants() { return $this->belongsToMany(Party::class, 'communication_participants')->withPivot('role'); }  
public function documents() { return $this->belongsToMany(Document::class, 'communication_documents'); }  

// Party.php (existing address book model)  
public function communications() { return $this->belongsToMany(Communication::class, 'communication_participants'); }  

// Document.php (existing document model)  
public function communications() { return $this->belongsToMany(Communication::class, 'communication_documents'); }  
```

---

### Key Implementation Plan
**1. Database Migrations**
- Create `threads`, `communications`, `communication_participants`, and `communication_documents` tables.

**2. LiveWire Components**
- **`CorrespondenceDashboard`**: Lists threads (sorted by `sent_at` descending by default).
- **`CreateThreadModal`**: Form to start a new thread (title, case file association).
- **`ThreadView`**: Displays all communications in a thread, with options to add new ones.
- **`AddCommunicationForm`**: Form to log a communication (senders/recipients picker, type, content/documents).

**3. UI/UX Recommendations**
- **Thread List**: Group by case file, show latest communication date, and status.
- **Chronological Sorting**: Communications within a thread should display in ascending order (oldest first) for linear readability, but allow users to toggle sorting.
- **Sender/Recipient Selection**: Use a searchable multi-select dropdown populated from the `parties` table.
- **Document Attachment**: Allow users to search/select from existing documents in their case file’s bucket.

---

### Workflow Steps
1. **Start a Thread**
    - User clicks "New Thread" on the Correspondence Dashboard.
    - Selects a case file, adds a title (e.g., "Negotiation with XYZ Corp"), and saves.

2. **Log a Communication**
    - Inside the thread, click "Add Communication".
    - Select senders/recipients (parties), type (email/letter/etc.), date, and either:
        - Attach documents (from the case’s document bucket), or
        - Paste plain text (e.g., email body).

3. **Track Progress**
    - Threads show status (open/closed) and all communications in chronological order.
    - Users can filter threads by case file, participant, or date range.

---

### Edge Cases & Solutions
- **Multiple Senders/Recipients**: Use the `communication_participants` pivot to track all parties.
- **Missing Documents**: Allow pasting text as a fallback.
- **Date Sorting**: Always use `sent_at` for sorting (not `created_at`).

---

### Example Query
To fetch all communications in a thread with participants:
```php  
$thread = Thread::with(['communications' => function ($query) {  
    $query->orderBy('sent_at', 'asc')  
          ->with(['participants', 'documents']);  
}])->find($threadId);  
```
