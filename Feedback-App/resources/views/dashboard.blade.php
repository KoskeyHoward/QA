<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold text-success fs-2">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container border-0">
            <div class="shadow-sm card hover-shadow">
                <div class="p-4 border-none card-body">
                    <!-- Tab Navigation -->
                    <ul class="mb-4 border-0 nav nav-tabs">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" 
                               class="nav-link @if(!request()->has('tab')) active fw-bold text-success @else text-muted @endif">
                                All Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard', ['tab' => 'pending']) }}" 
                               class="nav-link @if(request('tab') === 'pending') active fw-bold text-success @else text-muted @endif">
                                Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard', ['tab' => 'approved']) }}" 
                               class="nav-link @if(request('tab') === 'approved') active fw-bold text-success @else text-muted @endif">
                                Approved
                            </a>
                        </li>
                    </ul>

                    <!-- Feedback Table -->
                    <div class="rounded table-responsive">
                        @php
                            $currentTab = request('tab');
                            $showActions = in_array($currentTab, ['pending', 'approved']);
                        @endphp

                        @if(($currentTab === 'pending' && $pendingFeedback->count() > 0) || 
                            ($currentTab === 'approved' && $approvedFeedback->count() > 0) || 
                            (!$currentTab && ($pendingFeedback->count() > 0 || $approvedFeedback->count() > 0)))
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Rating</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        @if($showActions)
                                            <th scope="col">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentTab === 'pending' ? $pendingFeedback : ($currentTab === 'approved' ? $approvedFeedback : $pendingFeedback->concat($approvedFeedback)) as $feedback)
                                    <tr>
                                        <td class="fw-semibold">{{ $feedback->name }}</td>
                                        <td>{{ $feedback->email }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $feedback->message }}</td>
                                        <td class="text-warning">
                                            @if($feedback->rating)
                                                {{ str_repeat('★', $feedback->rating) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge @if($feedback->approved) bg-success @else bg-warning text-dark @endif">
                                                {{ $feedback->approved ? 'Approved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                                        @if($showActions)
                                            <td>
                                                <div class="gap-2 d-flex">
                                                    @if(!$feedback->approved)
                                                        <form action="{{ route('feedback.approve', $feedback) }}" method="POST" class="toast-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="bi bi-check-circle me-1"></i>
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($feedback->approved)
                                                        <form action="{{ route('feedback.disapprove', $feedback) }}" method="POST" class="d-inline toast-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning btn-sm">
                                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                                Disapprove
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('feedback.destroy', $feedback) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="p-5 text-center rounded bg-light">
                                <i class="bi bi-emoji-frown fs-1 text-muted"></i>
                                <p class="mt-3 fs-5 text-muted">No feedback found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 and Toastr CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.1/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        // Configure Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000
        };

        // Handle form submissions with toast notifications
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept form submissions for approve/disapprove
            document.querySelectorAll('.toast-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    
                    fetch(form.action, {
                        method: form.method,
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.toast) {
                            toastr[data.toast.type](data.toast.message, data.toast.title);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('An error occurred', 'Error');
                    });
                });
            });

            // Handle delete form submissions with SweetAlert2 confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const submitButton = form.querySelector('button[type="submit"]');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitButton.disabled = true;
                            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                            
                            fetch(form.action, {
                                method: 'POST', // Laravel uses POST with _method=DELETE
                                body: new FormData(form),
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => {
                                if (response.redirected) {
                                    window.location.href = response.url;
                                    return;
                                }
                                return response.json();
                            })
                            .then(data => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = '<i class="bi bi-trash me-1"></i> Delete';
                                if (data && data.toast) {
                                    toastr[data.toast.type](data.toast.message, data.toast.title);
                                    // Remove the table row after successful deletion
                                    form.closest('tr').remove();
                                }
                            })
                            .catch(error => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = '<i class="bi bi-trash me-1"></i> Delete';
                                console.error('Error:', error);
                                toastr.error('An error occurred while deleting', 'Error');
                            });
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>