<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $userId = auth()->id();

        $tasks = Task::query()
            ->where('user_id', $userId)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(5)
            ->withQueryString();

        return view('task.index', compact('tasks'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'deadline'    => 'required|date',
            'status'      => 'required|in:pending,in-progress,completed',
        ]);

        Task::create([
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'deadline'    => $validated['deadline'],
            'status'      => $validated['status'],
        ]);

        return redirect()
            ->route('task.index')
            ->with([
                'pesan'       => 'Data berhasil disimpan.',
                'alert-class' => 'alert-success',
            ]);
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'deadline'    => 'required|date',
            'status'      => 'required|in:pending,in-progress,completed',
        ]);

        $task->update([
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'deadline'    => $validated['deadline'],
            'status'      => $validated['status'],
        ]);

        return redirect()
            ->route('task.index')
            ->with([
                'pesan'       => 'Data berhasil diperbarui.',
                'alert-class' => 'alert-success',
            ]);
    }

    public function updateStatus(Task $task, $status)
    {
        $validStatuses = ['pending', 'in-progress', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('pesan', 'Status tidak valid.')->with('alert-class', 'alert-danger');
        }

        $task->update([
            'status' => $status
        ]);

        return back()->with('pesan', 'Status berhasil diperbarui.')->with('alert-class', 'alert-success');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()
            ->route('task.index')
            ->with([
                'pesan'         => 'Data berhasil dihapus.',
                'alert-class'   => 'alert-danger'
            ]);
    }
}
