<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\AttendanceStatus;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendancesImport implements ToModel, WithStartRow, WithHeadingRow, WithValidation, SkipsOnFailure {

    use Importable, SkipsFailures;

    private $rows = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row) {

        ++$this->rows;

        $status = AttendanceStatus::where('attendancestatus_title', $row['status'])->first();
        $attendance_status = $status ? $status->attendancestatus_id : 1;

        $in_time = null;
        if($row['in_time']) {
            $intimeHours = $row['in_time'] * 24;
            $inhours = floor($intimeHours);
            $inminutes = ($intimeHours - $inhours) * 60;
            $in_time = sprintf('%02d:%02d', $inhours, round($inminutes));
        }

        $out_time = null;
        if($row['out_time']) {
            $outtimeHours = $row['out_time'] * 24;
            $outhours = floor($outtimeHours);
            $outminutes = ($outtimeHours - $outhours) * 60;
            $out_time = sprintf('%02d:%02d', $outhours, round($outminutes));
        }

        $baseDate = Carbon::createFromDate(1900, 1, 1);
        $realDate = $baseDate->addDays($row['date'] - 2);

        return new Attendance([
            'attendance_importid' => request('import_ref'),
            'user_id' => $row['team_member_id'],
            'date' => $realDate->toDateString(),
            'in_time' => $in_time,
            'out_time' => $out_time,
            'reason' => $row['reason'] ?? '',
            'attendance_status' => $attendance_status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'team_member_id' => [
                'required',
            ],
            'date' => [
                'required',
            ],
        ];
    }

    /**
     * we are ignoring the header and so we will start with row number (2)
     * @return int
     */
    public function startRow(): int {
        return 2;
    }

    /**
     * lets count the total imported rows
     * @return int
     */
    public function getRowCount(): int {
        return $this->rows;
    }
}
