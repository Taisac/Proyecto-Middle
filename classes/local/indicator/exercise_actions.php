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
 * Exercise actions indicator.
 *
 * @package   tool_inspire
 * @copyright 2016 David Monllao {@link http://www.davidmonllao.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_inspire\local\indicator;

defined('MOODLE_INTERNAL') || die();

/**
 * Exercise actions indicator.
 *
 * @package   tool_inspire
 * @copyright 2016 David Monllao {@link http://www.davidmonllao.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exercise_actions extends linear {

    public static function get_name() {
        return get_string('indicator:exerciseactions', 'tool_inspire');
    }

    public static function required_sample_data() {
        // User is not required, calculate_sample can handle its absence.
     //   return array('context');
	return null;
    }

    public function calculate_sample($sampleid, $sampleorigin, $starttime = false, $endtime = false) {
        global $DB;

        $select = '';
        $params = array();
	$usuario = '';

        if ($user = $this->retrieve('user', $sampleid)) {
            $params = $params + array('userid' => $user->id);
        }

	$nrecords = $DB->count_records_sql("select count(*) from mdl_logstore_standard_log l, mdl_course_modules c where userid = :userid and l.contextinstanceid = c.id and (l.action = 'created' or l.action = 'updated') and c.idnumber like 'E%'", $params);
	if (empty($nrecords)){
		$nrecords = "Sin datos";
	}

	return $nrecords;
    }
}
