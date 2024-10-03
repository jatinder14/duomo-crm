<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @filefolder    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\FileFolder;
use Illuminate\Http\Request;

class FileFolderRepository {

    /**
     * The leads repository instance.
     */
    protected $filefolder;

    /**
     * Inject dependecies
     */
    public function __construct(FileFolder $filefolder) {
        $this->filefolder = $filefolder;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object filefolders collection
     */
    public function search($id = '', $data = []) {

        $filefolders = $this->filefolder->newQuery();

        //joins
        $filefolders->leftJoin('users', 'users.id', '=', 'file_folders.filefolder_creatorid');

        // all fields
        $filefolders->selectRaw('*');

        //default where
        $filefolders->whereRaw("1 = 1");

        //filters:
        if (request()->filled('filter_filefolder_system')) {
            $filefolders->where('filefolder_system', request('filter_filefolder_system'));
        }

        if (is_numeric($id)) {
            $filefolders->where('filefolder_id', $id);
        }

        //sorting
        $filefolders->orderBy('filefolder_name', 'asc');

        // Get the results and return them.
        return $filefolders->paginate(1000);
    }

    /**
     * some notes
     *
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function addDefault($project_id) {

        //get default folder
        $default_folder = \App\Models\FileFolder::Where('filefolder_system', 'yes')->Where('filefolder_default', 'yes')->first();

        $folder = new \App\Models\FileFolder();
        $folder->filefolder_creatorid = 0;
        $folder->filefolder_projectid = $project_id;
        $folder->filefolder_name = $default_folder->filefolder_name;
        $folder->filefolder_default = 'yes';
        $folder->filefolder_system = 'no';
        $folder->save();

        //add all other folers
        $default_folders = \App\Models\FileFolder::Where('filefolder_system', 'yes')->Where('filefolder_default', 'no')->orderBy('filefolder_name', 'asc')->get();
        foreach ($default_folders as $default_folder) {
            $folder = new \App\Models\FileFolder();
            $folder->filefolder_creatorid = 0;
            $folder->filefolder_projectid = $project_id;
            $folder->filefolder_name = $default_folder->filefolder_name;
            $folder->filefolder_default = 'no';
            $folder->filefolder_system = 'no';
            $folder->save();
        }
    }


}