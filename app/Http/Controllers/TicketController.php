<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

// ✅ Import Form Request classes
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;

/**
 * TicketController
 * 
 * Controller untuk mengelola tiket dengan Input Validation.
 * 
 * PERBEDAAN DENGAN VALIDASI DI CONTROLLER:
 * 
 * ❌ SEBELUM (Validasi inline - kotor):
 * public function store(Request $request) {
 *     $validated = $request->validate([
 *         'title' => 'required|string|max:255',
 *         'description' => 'required|string|min:20',
 *         // ... 10+ rules lainnya
 *     ], [
 *         'title.required' => 'Judul wajib diisi.',
 *         // ... 20+ custom messages
 *     ]);
 *     // Controller jadi panjang dan sulit dibaca
 * }
 * 
 * ✅ SESUDAH (Form Request - bersih):
 * public function store(StoreTicketRequest $request) {
 *     Ticket::create($request->validated());
 *     // Controller singkat dan fokus pada logic
 * }
 * 
 * Materi Minggu 3 - Hari 2: Input Validation
 */
class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(): View
    {
        // Ambil semua tiket, urutkan dari terbaru
        $tickets = Ticket::latest()->paginate(10);
        
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create(): View
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket in storage.
     * 
     * ✅ MENGGUNAKAN FORM REQUEST: StoreTicketRequest
     * 
     * FLOW:
     * 1. Request masuk
     * 2. StoreTicketRequest OTOMATIS dipanggil
     * 3. authorize() dicek → jika false, throw 403
     * 4. prepareForValidation() dijalankan → trim, sanitize
     * 5. rules() divalidasi → jika gagal, redirect back dengan errors
     * 6. passedValidation() dijalankan → sanitasi tambahan
     * 7. Jika SEMUA OK, baru masuk ke method ini
     * 8. $request->validated() berisi data yang sudah bersih & valid
     * 
     * @param StoreTicketRequest $request  ← Ganti Request dengan StoreTicketRequest
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        // ✅ Validasi sudah OTOMATIS terjadi sebelum sampai di sini!
        // Jika ada error, user sudah di-redirect back dengan pesan error
        
        // $request->validated() hanya berisi field yang ada di rules()
        // dan sudah melewati validasi + sanitasi
        $validatedData = $request->validated();
        
        // ⚠️ TEMPORARY: Tambahkan user_id hardcode (belum ada auth - Minggu 4)
        // TODO: Ganti dengan Auth::id() di Minggu 4
        $validatedData['user_id'] = 1;
        
        // Set default status untuk tiket baru
        $validatedData['status'] = 'open';
        
        // Simpan ke database
        $ticket = Ticket::create($validatedData);
        
        // Redirect dengan flash message
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat!');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket): View
    {
        // Load relasi jika ada
        $ticket->load(['user', 'comments.user']);
        
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified ticket in storage.
     * 
     * ✅ MENGGUNAKAN FORM REQUEST: UpdateTicketRequest
     * 
     * PERBEDAAN DENGAN STORE:
     * - UpdateTicketRequest punya field 'status' tambahan
     * - Authorization bisa dicek (ownership) - nanti di Minggu 4
     * 
     * @param UpdateTicketRequest $request  ← Ganti Request dengan UpdateTicketRequest
     * @param Ticket $ticket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        // ✅ Validasi sudah OTOMATIS terjadi!
        // UpdateTicketRequest memvalidasi: title, description, status, priority
        
        // Update tiket dengan data yang sudah valid
        $ticket->update($request->validated());
        
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diperbarui!');
    }

    /**
     * Remove the specified ticket from storage.
     * 
     * Untuk delete, kita tidak perlu Form Request karena:
     * - Tidak ada input yang perlu divalidasi
     * - Authorization bisa dicek di middleware atau policy (Minggu 4)
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        // ⚠️ TEMPORARY: Tidak ada authorization check
        // TODO: Di Minggu 4, tambahkan policy atau middleware
        // Contoh: $this->authorize('delete', $ticket);
        
        $ticket->delete();
        
        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus!');
    }
}
