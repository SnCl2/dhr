@extends('layouts.admin')

@section('title', 'Inquiries Inbox - Propszy')
@section('page_title', 'Contact Us Inquiries')

@section('content')
<div class="glass-dark rounded-3xl overflow-hidden shadow-xl mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-850 bg-slate-900/40 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <th class="p-6">Sender Details</th>
                    <th class="p-6">Subject & Message</th>
                    <th class="p-6">Status</th>
                    <th class="p-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850 text-sm">
                @forelse($inquiries as $inq)
                <tr class="text-slate-300 hover:bg-slate-900/20">
                    <td class="p-6">
                        <span class="block font-semibold text-white">{{ $inq->name }}</span>
                        <span class="block text-xs text-purple-400 mt-0.5">{{ $inq->email }}</span>
                        @if($inq->phone)
                            <span class="block text-xs text-slate-500 mt-0.5">{{ $inq->phone }}</span>
                        @endif
                        <span class="block text-[10px] text-slate-500 mt-1">Received: {{ $inq->created_at->format('d-M-Y h:i A') }}</span>
                    </td>
                    <td class="p-6 max-w-md">
                        <span class="block font-semibold text-slate-200 mb-1">{{ $inq->subject }}</span>
                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ \Illuminate\Support\Str::limit($inq->message, 80) }}</p>
                    </td>
                    <td class="p-6">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-semibold uppercase tracking-wider 
                            @if($inq->status === 'unread') bg-pink-500/10 text-pink-400 border border-pink-500/20
                            @elseif($inq->status === 'replied') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                            @else bg-slate-800 text-slate-400 border border-slate-750
                            @endif">
                            {{ $inq->status }}
                        </span>
                    </td>
                    <td class="p-6 text-right space-x-2">
                        <button type="button" onclick="openInquiryModal({{ json_encode($inq) }})" 
                                class="px-3.5 py-2 bg-purple-500/10 hover:bg-purple-500/20 text-xs font-semibold text-purple-400 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-eye mr-1.5"></i> View Details
                        </button>
                        @if($inq->status === 'unread')
                        <form action="{{ route('admin.inquiries.reply', $inq) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs font-semibold text-emerald-400 hover:text-white rounded-xl transition-all">
                                <i class="fa-solid fa-check mr-1.5"></i> Mark Replied
                            </button>
                        </form>
                        @else
                            <span class="inline-flex items-center text-xs text-slate-500 py-2"><i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> Resolved</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-slate-500 text-base">
                        <i class="fa-solid fa-envelope-open text-3xl mb-4 block"></i>
                        No contact inquiries found in database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($inquiries->hasPages())
    <div class="px-6 py-4 border-t border-slate-850">
        {{ $inquiries->links() }}
    </div>
    @endif
</div>

<!-- View Inquiry Modal -->
<div id="inquiry-modal" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-slate-950/85 backdrop-blur-sm">
    <div class="glass-dark border border-slate-800 w-full max-w-2xl mx-4 rounded-3xl shadow-2xl p-6 sm:p-8 relative">
        <button onclick="closeInquiryModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h3 class="font-outfit font-bold text-xl text-white mb-6 flex items-center">
            <i class="fa-solid fa-envelope-open text-purple-400 mr-2.5"></i> Inquiry Details
        </h3>

        <div class="space-y-6">
            <!-- Sender details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-900/40 p-4 rounded-2xl border border-slate-850">
                <div>
                    <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Sender Name</span>
                    <span id="modal-sender-name" class="block font-semibold text-white mt-0.5"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Received Date</span>
                    <span id="modal-date" class="block text-slate-300 mt-0.5 text-xs"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Email Address</span>
                    <a id="modal-sender-email" href="" class="block text-purple-400 hover:underline mt-0.5 text-xs"></a>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Phone Number</span>
                    <span id="modal-sender-phone" class="block text-slate-300 mt-0.5 text-xs"></span>
                </div>
            </div>

            <!-- Subject -->
            <div>
                <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Subject</span>
                <h4 id="modal-subject" class="text-base font-bold text-slate-100 mt-1"></h4>
            </div>

            <!-- Message body -->
            <div>
                <span class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider">Message</span>
                <div class="mt-2 bg-slate-900/60 p-5 rounded-2xl border border-slate-850 text-slate-300 text-sm leading-relaxed whitespace-pre-line overflow-y-auto max-h-60" id="modal-message">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-8">
            <button onclick="closeInquiryModal()" class="px-5 py-2.5 bg-slate-900 border border-slate-850 text-slate-400 hover:text-white rounded-xl text-xs font-semibold transition-all">
                Close
            </button>
            <form id="modal-reply-form" action="" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-purple-500/10 transition-all">
                    <i class="fa-solid fa-check mr-1.5"></i> Mark Replied
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openInquiryModal(inq) {
    document.getElementById('modal-sender-name').textContent = inq.name;
    document.getElementById('modal-sender-email').textContent = inq.email;
    document.getElementById('modal-sender-email').href = 'mailto:' + inq.email;
    document.getElementById('modal-sender-phone').textContent = inq.phone || 'N/A';
    document.getElementById('modal-date').textContent = new Date(inq.created_at).toLocaleString();
    document.getElementById('modal-subject').textContent = inq.subject;
    document.getElementById('modal-message').textContent = inq.message;
    
    const replyForm = document.getElementById('modal-reply-form');
    if (inq.status === 'unread') {
        replyForm.classList.remove('hidden');
        replyForm.action = "{{ url('admin/inquiries') }}/" + inq.id + "/reply";
    } else {
        replyForm.classList.add('hidden');
    }
    
    document.getElementById('inquiry-modal').classList.remove('hidden');
}

function closeInquiryModal() {
    document.getElementById('inquiry-modal').classList.add('hidden');
}

// Close modal when clicking outside content area
document.getElementById('inquiry-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeInquiryModal();
    }
});
</script>
@endsection
