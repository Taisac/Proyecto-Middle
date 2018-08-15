<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Viewed actions indicator.
 *
 * @package   tool_middle
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_inspire\local\indicator;

defined('MOODLE_INTERNAL') || die();

/**
 * Viewed actions indicator.
 * 
 * Returns a number of views a user makes.
 *
 * @package   tool_middle
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class viewed_actions extends linear {

    public static function get_name() {
        return get_string('indicator:viewedactions', 'tool_inspire');
    }

    public static function required_sample_data() {
	return null;
    }

    public function calculate_sample($sampleid, $sampleorigin, $starttime = false, $endtime = false) {
        global $DB;

        $select = '';
        $params = array();
	$limit_views = 50;
	$max = $DB->count_records_sql("select count(*) as cant from mdl_logstore_standard_log l where l.action='viewed' group by userid order by cant desc limit 1",$params);


        if ($user = $this->retrieve('user', $sampleid)) {
            $select .= "userid = :userid AND ";
            $params = $params + array('userid' => $user->id);
        }

        $context = $this->retrieve('context', $sampleid);
        $select .= "contextlevel = :contextlevel AND contextinstanceid = :contextinstanceid AND " .
            "action = 'viewed' AND timecreated > :starttime AND timecreated <= :endtime";
        $params = $params + array('contextlevel' => $context->contextlevel,
            'contextinstanceid' => $context->instanceid, 'starttime' => $starttime, 'endtime' => $endtime);
        $nrecords = $DB->count_records_select('logstore_standard_log', $select, $params);

	$limit1 = $max * 0.2;
	$limit2 = $max * 0.4;
	$limit3 = $max * 0.6;
	$limit4 = $max * 0.8;
	

	if ($nrecords < $limit1){
		return 1;
	}elseif (($nrecords >= $limit1) && ($nrecords < $limit2)){
		return 2;
	}elseif (($nrecords >= $limit2) && ($nrecords < $limit3)){
		return 3;
	}elseif (($nrecords >= $limit3) && ($nrecords < $limit4)){
		return 4;
	}else{
		return 5;

	}

    }
}
