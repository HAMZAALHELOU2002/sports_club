<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Http\Traits\imageTrait;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SectionController extends Controller
{
    use GeneralTrait;
    use imageTrait;

    public function index()
    {
        $sections = Section::where('club_id', Auth::user()->club_id)->with('club')->get();
        $playersWithUrls = $sections->map(function ($section) {
            if ($section->image_path !== null)
                $section->image_path = url('attachments/sections/' . $section->club_id . '/' . $section->id . '/' . $section->image_path);
            return $section;
        });
        return response()->json($playersWithUrls);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                "name_en" => "required|string",
                "number" => "required|integer",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $sections = new Section();
            $sections->club_id = Auth::user()->club_id;
            $sections->name = ['en' => $request->name_en, 'ar' => $request->name_ar];
            $sections->number = $request->number;
            $sections->section_description = $request->section_description;
            $sections->department_address = $request->department_address;
            $sections->save();
            if ($request->hasfile('image_path')) {
                $section_image = $this->saveImage($request->image_path, 'attachments/sections/' . Auth::user()->club_id . '/' . $sections->id);
                $sections->image_path = $section_image;
                $sections->save();
                $sections->image_path = url('attachments/sections/' . $sections->club_id . '/' . $sections->id . '/' . $sections->image_path);
            }
            DB::commit();  // insert data
            return response()->json([
                'status' => true,
                'date' => $sections,
                'message' => 'section  Added Successfully',
            ]);
        } catch (\Throwable $ex) {
            //            وهنا يعمل رجوع عن الحفظ
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function show(Request $request)
    {
        $section = Section::where('club_id', Auth::user()->club_id)->with('club')->find($request->id);
        if (!$section) {
            return response()->json([
                'status' => 'Error',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'couch not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        if ($section->image_path !== null)
            $section->image_path = url('attachments/sections/' . $section->club_id . '/' . $section->id . '/' . $section->image_path);

        return response()->json($section);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                "name_en" => "required|string",
                "number" => "required|integer",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $sections = Section::where('club_id', Auth::user()->club_id)->find($request->id);
//            dd($request);
            if (!$sections) {
                return response()->json([
                    'status' => 'Error',
                    'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                    'message' => 'Section not found',
                    'data' => []
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
            $sections->name = ['en' => $request->name_en, 'ar' => $request->name_ar];
            $sections->number = $request->number;
            $sections->section_description = $request->section_description;
            $sections->department_address = $request->department_address;
            $sections->save();
            if ($request->hasfile('image_path')) {
                $this->deleteFile('sections', $request->id);
                $section_image = $this->saveImage($request->image_path, 'attachments/sections/' . Auth::user()->club_id . '/' . $sections->id);
                $sections->image_path = $section_image;
                $sections->save();
                $sections->image_path = url('attachments/sections/' . $sections->club_id . '/' . $sections->id . '/' . $sections->image_path);

            }
            DB::commit();  // insert data

            return response()->json([
                'status' => true,
                'data' => $sections,
                'message' => 'Section Information Updated Successfully',
            ]);

        } catch (\Throwable $ex) {
            //            وهنا يعمل رجوع عن الحفظ
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $sections = Section::where('club_id', Auth::user()->club_id)->find($request->id);
        if (!$sections) {
            return response()->json([
                'status' => 'Error',
                'status_code' => ResponseAlias::HTTP_NOT_FOUND,
                'message' => 'couch not found',
                'data' => []
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        $this->deleteFile('sections', $request->id);
        $sections->delete();
        return response()->json([
            'status' => true,
            'message' => 'section deleted Successfully',
        ]);
    }
}
