<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $search = is_null($request->q) ? '' : $request->q;
        $order = is_null($request->order) ? 'asc' : $request->order;
        $orderBy = is_null($request->orderBy) ? 'created_at' : $request->orderBy;

        $courses = Course::with('status')
            ->where('company_id', Auth::user()->company_id)
            ->whereIn('status_id', [1, 2]) // Draft - 1, Published - 2
            ->when($search, fn($query) => $query->where('title', 'LIKE', "%{$search}%"))
            ->when(in_array($order, ['asc', 'desc']), function ($query) use ($orderBy, $order) {
                if ($orderBy === 'title') {
                    $query->orderByRaw('LOWER(title) ' . $order);
                } else {
                    $query->orderBy($orderBy, $order);
                }
            })
            ->paginate(10)
            ->through(fn($module) => [
                'id'            => $module->id,
                'title'         => $module->title,
                'status'        => $module->status->status,
            ]);

        return Inertia::render('courses/Courses', [
            'courses'   => $courses,
            'q'         => $search,
            'order'     => $order,
            'orderBy'   => $orderBy,
        ]);
    }

    /**
     * @brief   Show the form to create a new course.
     * 
     * @return  Inertia\Response 
     *          Rendering of 'courses/Create' Vue component.
     */
    public function create(): InertiaResponse
    {
        $modules = Module::query()
            ->where('company_id', Auth::user()->company_id)
            ->get();

        return Inertia::render('courses/Create', [
            'modules' => $modules
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'moduleIds'   => 'array',
            'moduleIds.*' => 'string|exists:modules,id',
        ]);

        $course = Course::create([
            'title'      => $validated['title'],
            'company_id' => $request->user()->company_id,
        ]);

        $course->modules()->sync($validated['moduleIds'] ?? []);
    }

    public function show(Course $course)
    {
        return Inertia::render('courses/Show', [
            'course' => $course,
        ]);
    }
}
