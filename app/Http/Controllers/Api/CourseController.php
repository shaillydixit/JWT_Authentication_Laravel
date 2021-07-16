<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\User;

class CourseController extends Controller
{
    // API - Course Enrollment
    public function courseEnrollment(Request $request)
    {
        //validation
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'total_videos' => 'required'
        ]);

        //create course project
        $course = new Course();

        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;
        $course->save();
        //send response

        return response()->json([
            'status' => 1,
            'message' => 'course enrolled successfully',
        ]);
    }

    //API - Total Courses
    public function totalCourses()
    {
        //first we finding user id
        $id = auth()->user()->id;
        //then we finding courses enrolled
        $courses = User::find($id)->courses;

        return response()->json([
            'status' => 1,
            'message' => 'total courses enrolled',
            'data' => $courses
        ]);
    }

    //API - Delete Courses
    public function deleteCourse($id)
    {
        $user_id = auth()->user()->id;

        if (Course::where([
            'id' => $id,
            'user_id' => $user_id
        ])->exists()) {
            $course = Course::find($id);
            $course->delete();
            return response()->json([
                'status' => 1,
                'message' => 'course deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'course not found '
            ]);
        }
    }
}
