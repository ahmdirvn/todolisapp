<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;

class TaskController extends Controller
{
    protected $database;
    protected $table = 'tasks';
    public $uid;

    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->database = Firebase::database();
        $this->firebaseAuth = $firebaseAuth;

        try {
            $token = session('firebase_token');
            if (!$token) {
                throw new \Exception('Token tidak ditemukan');
            }
            $verifiedIdToken = $firebaseAuth->verifyIdToken($token);
            $this->uid = $verifiedIdToken->claims()->get('sub'); // UID Firebase
        } catch (\Throwable $e) {
            $this->uid = null;
        }
    }

    protected function getUid()
    {
        return $this->uid;
    }

    public function view(Request $request)
    {
        $tasks = $this->database->getReference($this->table)->getValue() ?? []; // pastikan array

        if ($request->ajax()) {
            // DataTables butuh JSON
            $result = [];
            foreach ($tasks as $id => $task) {
                $result[] = [
                    'id'          => $id,
                    'title'       => $task['title'] ?? '',
                    'description' => $task['description'] ?? '',
                    'status'      => $task['status'] ?? 'pending',
                    'priority'    => $task['priority'] ?? 'medium',
                    'due_date'    => $task['due_date'] ?? null,
                ];
            }
            return response()->json(['data' => $result]);
        }

        // Normal view
        return view('tasks.index', compact('tasks'));
    }


    // CREATE
    public function store(Request $request)
    {
        $uid = $this->getUid();

        $newTask = $this->database
            ->getReference($this->table)
            ->push([
                'title'       => $request->title,
                'priority'    => $request->priority ?? 'normal',
                'due_date'    => $request->due_date ?? null,
                'description' => $request->description ?? '',
                'status'      => $request->status ?? 'pending',
                'user_id'     => $uid, // simpan id user
            ]);

        return response()->json([
            'status' => 'success',
            'id'     => $newTask->getKey(),
            'data'   => $newTask->getValue()
        ]);
    }

    // READ ALL (khusus data milik user login)
    public function index()
    {
        $uid = $this->getUid();
        $tasks = $this->database->getReference($this->table)->getValue();

        $result = [];
        if ($tasks) {
            foreach ($tasks as $id => $task) {
                if (($task['user_id'] ?? null) === $uid) { // filter berdasarkan user_id
                    $result[] = [
                        'id'          => $id,
                        'title'       => $task['title'] ?? '',
                        'priority'    => $task['priority'] ?? 'normal',
                        'due_date'    => $task['due_date'] ?? null,
                        'description' => $task['description'] ?? '',
                        'status'      => $task['status'] ?? 'pending',
                    ];
                }
            }
        }

        return response()->json(['data' => $result]);
    }

    // UPDATE (hanya boleh update data milik user)
    public function update(Request $request, $id)
    {
        $uid = $this->getUid();
        $task = $this->database->getReference($this->table . '/' . $id)->getValue();

        if (!$task || ($task['user_id'] ?? null) !== $uid) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $this->database->getReference($this->table . '/' . $id)
            ->update([
                'title'       => $request->title,
                'priority'    => $request->priority ?? 'normal',
                'due_date'    => $request->due_date ?? null,
                'description' => $request->description ?? '',
                'status'      => $request->status ?? 'pending',
            ]);

        return response()->json(['status' => 'updated']);
    }

    // DELETE (hanya boleh hapus data milik user)
    public function destroy($id)
    {
        $uid = $this->getUid();
        $task = $this->database->getReference($this->table . '/' . $id)->getValue();

        if (!$task || ($task['user_id'] ?? null) !== $uid) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $this->database->getReference($this->table . '/' . $id)->remove();
        return response()->json(['status' => 'deleted']);
    }
}
