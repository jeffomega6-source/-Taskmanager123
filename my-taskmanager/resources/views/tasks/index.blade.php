<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($editingTask) ? 'Edit task' : 'Taskboard' }} | Personal Task Manager</title>
    <style>
        :root { --ink: #18302b; --muted: #6d7d78; --paper: #f4f6f1; --white: #fffdf8; --line: #dce4dc; --mint: #cce9d7; --green: #2f7659; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: radial-gradient(circle at 85% 5%, #dcefe1 0, transparent 25rem), var(--paper); font-family: Georgia, 'Times New Roman', serif; }
        button, input, textarea, select { font: inherit; }
        .shell { width: min(1180px, calc(100% - 40px)); margin: 0 auto; padding: 28px 0 60px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 24px; margin-bottom: 34px; }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 1.15rem; font-weight: bold; letter-spacing: .02em; }
        .mark { display: grid; place-items: center; width: 37px; height: 37px; border-radius: 12px 12px 12px 3px; color: var(--white); background: var(--green); font-family: Arial, sans-serif; }
        .date { color: var(--muted); font: 600 .74rem Arial, sans-serif; letter-spacing: .13em; text-transform: uppercase; }
        .intro { display: flex; justify-content: space-between; align-items: end; gap: 30px; margin-bottom: 22px; }
        h1 { max-width: 650px; margin: 0; font-size: clamp(2.6rem, 5vw, 4.5rem); line-height: .95; font-weight: 400; letter-spacing: -.04em; }
        .intro p { max-width: 260px; margin: 0 0 5px; color: var(--muted); font: .9rem/1.5 Arial, sans-serif; }
        .intro-actions { display: flex; flex-direction: column; align-items: end; gap: 14px; }
        .quick-action { padding: 11px 16px; color: var(--white); background: var(--green); box-shadow: 0 4px 0 #1f513d; font: bold .73rem Arial, sans-serif; letter-spacing: .05em; text-decoration: none; text-transform: uppercase; }
        .quick-action:hover { background: #245c45; }
        .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 150px)); gap: 10px; margin-bottom: 28px; }
        .stat { min-width: 0; padding: 15px 17px; border: 1px solid var(--line); border-top: 3px solid var(--green); background: rgba(255,253,248,.72); }
        .stat strong { display: block; font-size: 1.65rem; font-weight: 400; }
        .stat span { color: var(--muted); font: .67rem Arial, sans-serif; letter-spacing: .11em; text-transform: uppercase; }
        .layout { display: grid; grid-template-columns: minmax(0, 1fr) 350px; gap: 26px; align-items: start; }
        .panel { border: 1px solid var(--line); background: rgba(255,253,248,.88); }
        .form-panel { border-top: 4px solid var(--green); }
        .panel-heading { display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 20px 22px; border-bottom: 1px solid var(--line); }
        h2 { margin: 0; font-size: 1.35rem; font-weight: 400; }
        .task-list { padding: 7px 22px; }
        .task { display: grid; grid-template-columns: 1fr auto; gap: 15px; padding: 20px 0; border-bottom: 1px solid var(--line); }
        .task:last-child { border-bottom: 0; }
        .task h3 { margin: 0 0 7px; font-size: 1.22rem; font-weight: 400; }
        .task p { margin: 0 0 10px; color: var(--muted); font: .87rem/1.45 Arial, sans-serif; }
        .meta { display: flex; flex-wrap: wrap; gap: 7px; align-items: center; color: var(--muted); font: .68rem Arial, sans-serif; letter-spacing: .04em; }
        .pill { padding: 5px 9px; border-radius: 20px; font-weight: bold; }
        .pending { color: #96691a; background: #fff0c8; } .completed { color: var(--green); background: var(--mint); }
        .task-actions { display: flex; align-items: start; gap: 7px; }
        .icon-button { padding: 8px 10px; border: 1px solid transparent; border-radius: 5px; color: var(--muted); background: #eef2ee; cursor: pointer; font: 600 .72rem Arial, sans-serif; text-decoration: none; transition: background .15s ease, color .15s ease, transform .15s ease; }
        .icon-button:hover { transform: translateY(-1px); }
        .complete-button { color: #236c54; background: #d8f0e2; }
        .complete-button:hover { color: #174a39; background: #bfe5cf; }
        .edit-button { color: #88601b; background: #fff0c9; }
        .edit-button:hover { color: #694710; background: #ffe5a2; }
        .delete-button { color: #a84e42; background: #fbe2dc; }
        .delete-button:hover { color: #87362c; background: #f5c9bf; }
        .empty { padding: 46px 10px; color: var(--muted); text-align: center; font-size: 1.1rem; }
        form.create { padding: 22px; }
        .field-help { margin: -9px 0 17px; color: var(--muted); font: .72rem Arial, sans-serif; }
        label { display: block; margin: 0 0 7px; color: var(--muted); font: bold .68rem Arial, sans-serif; letter-spacing: .1em; text-transform: uppercase; }
        input, textarea, select { width: 100%; margin-bottom: 17px; padding: 11px 12px; border: 1px solid var(--line); border-radius: 0; color: var(--ink); background: #fff; outline: none; }
        input:focus, textarea:focus, select:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(47,118,89,.1); }
        textarea { min-height: 100px; resize: vertical; }
        .submit { width: 100%; padding: 14px 16px; border: 0; color: var(--white); background: var(--green); cursor: pointer; font: bold .76rem Arial, sans-serif; letter-spacing: .08em; text-transform: uppercase; }
        .submit:hover { background: #245c45; }
        .cancel { display: block; margin-top: 13px; padding: 9px; color: #7f5d23; background: #fff7df; text-align: center; font: 600 .75rem Arial, sans-serif; text-decoration: none; }
        .alert { margin-bottom: 22px; padding: 12px 15px; color: var(--green); border-left: 4px solid var(--green); background: var(--mint); font: .85rem Arial, sans-serif; }
        .errors { margin: 0 0 18px; padding: 12px 15px; color: #9b4b3a; background: #fde5df; font: .8rem/1.5 Arial, sans-serif; }
        @media (max-width: 800px) { .shell { width: min(100% - 28px, 600px); padding-top: 22px; } .topbar { margin-bottom: 30px; } .intro { display: block; } .intro p { margin-top: 16px; } .intro-actions { align-items: start; margin-top: 18px; } .layout { grid-template-columns: 1fr; } .form-panel { order: -1; } }
        @media (max-width: 480px) { .task { grid-template-columns: 1fr; } .task-actions { justify-content: flex-start; flex-wrap: wrap; } .date { display: none; } .stats { grid-template-columns: repeat(3, minmax(0, 1fr)); } .stat { padding: 12px 10px; } .stat strong { font-size: 1.4rem; } .stat span { font-size: .6rem; letter-spacing: .05em; } }
    </style>
</head>
<body>
<main class="shell">
    <header class="topbar"><div class="brand"><span class="mark">✓</span> Personal Task Manager</div><div class="date">{{ now()->format('l, F j, Y') }}</div></header>
    @if (session('success')) <div class="alert">{{ session('success') }}</div> @endif
    @if ($errors->any()) <div class="errors">{{ $errors->first() }}</div> @endif
    <section class="intro"><div><h1>Make space for<br><em>what matters.</em></h1></div><div class="intro-actions"><p>A quiet place to collect your next steps, keep momentum, and finish well.</p><a class="quick-action" href="#task-form">+ New task</a></div></section>
    <section class="stats"><div class="stat"><strong>{{ $taskCount }}</strong><span>Total tasks</span></div><div class="stat"><strong>{{ $pendingCount }}</strong><span>To do</span></div><div class="stat"><strong>{{ $completedCount }}</strong><span>Completed</span></div></section>
    <div class="layout">
        <section class="panel"><div class="panel-heading"><h2>Your task list</h2><span class="date">{{ $taskCount }} {{ Str::plural('item', $taskCount) }}</span></div><div class="task-list" aria-live="polite">
            @forelse ($tasks as $task)
                <article class="task"><div><h3>{{ $task->task_name }}</h3>@if ($task->description)<p>{{ $task->description }}</p>@endif<div class="meta"><span class="pill {{ strtolower($task->status) }}">{{ $task->status }}</span>@if ($task->due_date)<span>Due {{ $task->due_date->format('M j, Y') }}</span>@else<span>No deadline</span>@endif</div></div><div class="task-actions"><form method="POST" action="{{ route('tasks.status', $task) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $task->status === 'Pending' ? 'Completed' : 'Pending' }}"><button class="icon-button complete-button" type="submit">{{ $task->status === 'Pending' ? 'Mark done' : 'Reopen' }}</button></form><a class="icon-button edit-button" href="{{ route('tasks.edit', $task) }}">Edit</a><form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">@csrf @method('DELETE')<button class="icon-button delete-button" type="submit">Delete</button></form></div></article>
            @empty <div class="empty">Your list is clear. Add a task to get started.</div> @endforelse
        </div></section>
        <aside class="panel form-panel" id="task-form"><div class="panel-heading"><h2>{{ isset($editingTask) ? 'Edit task' : 'Add a task' }}</h2></div><form class="create" method="POST" action="{{ isset($editingTask) ? route('tasks.update', $editingTask) : route('tasks.store') }}">@csrf @if (isset($editingTask)) @method('PUT') @endif<label for="task_name">Task name</label><input id="task_name" name="task_name" value="{{ old('task_name', $editingTask->task_name ?? '') }}" placeholder="What needs doing?" autocomplete="off" required><label for="description">Description</label><textarea id="description" name="description" placeholder="A little context (optional)">{{ old('description', $editingTask->description ?? '') }}</textarea><p class="field-help">Keep it short and specific so it is easy to act on.</p><label for="status">Status</label><select id="status" name="status"><option value="Pending" @selected(old('status', $editingTask->status ?? 'Pending') === 'Pending')>Pending</option><option value="Completed" @selected(old('status', $editingTask->status ?? '') === 'Completed')>Completed</option></select><label for="due_date">Due date</label><input id="due_date" type="date" name="due_date" value="{{ old('due_date', isset($editingTask) && $editingTask->due_date ? $editingTask->due_date->format('Y-m-d') : '') }}"><button class="submit" type="submit">{{ isset($editingTask) ? 'Save changes' : 'Add to list' }}</button>@if (isset($editingTask))<a class="cancel" href="{{ route('tasks.index') }}">Cancel editing</a>@endif</form></aside>
    </div>
</main>
</body>
</html>