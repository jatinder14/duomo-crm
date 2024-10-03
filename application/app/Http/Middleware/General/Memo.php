<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles precheck processes for setup processes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\General;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class Memo {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $line = $this->getLog1();

        //validate
        if (!request()->filled($line)) {
            abort(409, $this->getLog3());
        }

        //connect to updates
        try {
            $response = Http::asForm()->post($this->getLog4(), [
                'q' => request($line),
                'i' => request()->ip(),
                'u' => url()->current(),
                'h' => request()->getHost(),
                'v' => config('app.installed_version'),
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            abort(409, $this->conLog01());
        }

        Log::error("validation", ['response' => $response]);

        //validate - getLog01
        if (!$result = $response->json()) {
            abort(409, $this->conLog02());
        }

        //expected
        $expected = $this->conLog05();

        //validate - getLog02
        if (!isset($result[$expected])) {
            abort(409, $this->conLog03());
        }

        //validate - getLog02
        if ($result[$expected] != $this->conLog04()) {
            abort(409, $this->getLog2());
        }

        //save to session
        session([$line => request($line)]);

        return $next($request);
    }

    private function getLog1() {
        return str_replace('w', '', 'pwuwrwcwhwawswe_wcwowdwe');
    }

    private function getLog2() {
        return str_replace('q', '', 'qPqroqdquqcqt pquqrchqaqse qcqoqdqe qisq iqnvqaqlqid');
    }

    private function getLog3() {
        return str_replace('w', '', 'Pwrwodwuwcwt pwuwrcwhwaswe cwowdew wiwsw rewqwuwiwrewd');
    }

    private function getLog4() {
        return str_replace('q', '', 'hqtqtqpqs:q/qq/uqpqdqaqtqeqs.qgqrqoqwcqrqm.iqoq/lqiqcqeqnqse');
    }

    private function conLog01() {
        $var_memo_1 = str_replace('q', '', 'Uqnqabqlqe tqo conqnqeqcqt tqo lqicqeqnse qvqqalqiqdatqion seqrqveqr');
        $var_memo_2 = str_replace('q', '', ' qPqlqeqaqse qcqoqntqact suqpqpqorqtq@qgqroqwqcqrm.qiqo - [qeqrqroqrq-q0q0q1q]');
        return $var_memo_1 . $var_memo_2;
    }

    private function conLog02() {
        $var_memo_1 = str_replace('q', '', 'Uqnqabqlqe tqo conqnqeqcqt tqo lqicqeqnse qvqqalqiqdatqion seqrqveqr');
        $var_memo_2 = str_replace('q', '', ' qPqlqeqaqse qcqoqntqact suqpqpqorqtq@qgqroqwqcqrm.qiqo - [qeqrqroqrq-q0q0q2q]');
        return $var_memo_1 . $var_memo_2;
    }

    private function conLog03() {
        $var_memo_1 = str_replace('q', '', 'Uqnqabqlqe tqo conqnqeqcqt tqo lqicqeqnse qvqqalqiqdatqion seqrqveqr');
        $var_memo_2 = str_replace('q', '', ' qPqlqeqaqse qcqoqntqact suqpqpqorqtq@qgqroqwqcqrm.qiqo - [qeqrqroqrq-q0q0q3q]');
        return $var_memo_1 . $var_memo_2;
    }

    private function conLog04() {
        return str_replace('q', '', 'vqaqlqiqdqq');
    }

    private function conLog05() {
        return str_replace('q', '', 'qsqtqaqtquqsq');
    }

}