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
 * Renderer.
 *
 * @package    tool_inspire
 * @copyright  2016 David Monllao {@link http://www.davidmonllao.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_inspire\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use templatable;
use renderable;

/**
 * Renderer class.
 *
 * @package    tool_inspire
 * @copyright  2016 David Monllao {@link http://www.davidmonllao.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Defer to template.
     *
     * @param templatable $renderable
     * @return string HTML
     */
    protected function render_models_list(templatable $renderable) {
        $data = $renderable->export_for_template($this);
        return parent::render_from_template('tool_inspire/models_list', $data);
    }

    /**
     * Renders the list of predictions
     *
     * @param renderable $renderable
     * @return string HTML
     */
    protected function render_predictions_list(renderable $renderable) {
        $data = $renderable->export_for_template($this);
        return parent::render_from_template('tool_inspire/predictions_list', $data);
    }

    /**
     * Renders a prediction
     *
     * @param renderable $renderable
     * @return string HTML
     */
    protected function render_prediction(renderable $renderable) {
        $data = $renderable->export_for_template($this);
        return parent::render_from_template('tool_inspire/prediction_details', $data);
    }

    /**
     * Renders a table.
     *
     * @param \table_sql $table
     * @return string HTML
     */
    public function render_table(\table_sql $table) {

        ob_start();
        $table->out(10, true);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Web interface evaluate results.
     *
     * @param \stdClass[] $results
     * @param string[] $executionlog
     * @return string HTML
     */
    public function render_evaluate_results($results, $executionlog = array()) {
        global $OUTPUT;

//        $output = $OUTPUT-> notification('Salida del output de RENDERER', \core\output\notification::NOTIFY_SUCCESS);

//	$output .= $OUTPUT-> notification($results, \core\output\notification::NOTIFY_WARNING);
	if (empty($results)){
		$output .= $OUTPUT-> notification('NO HAY RESULTADOS...' , \core\output\notification::NOTIFY_WARNING);
	} else {
		$output .= $OUTPUT-> notification(' ' , \core\output\notification::NOTIFY_WARNING);
}



$longitud = count($results);

/*
for ($i=0; $i<$longitud; $i++){
	$output .= $OUTPUT-> notification('Contiene: ' .  $results[$i] , \core\output\notification::NOTIFY_WARNING);
}
*/

	$output .= $OUTPUT-> notification('Hay ' .  $longitud . ' resultados...' , \core\output\notification::NOTIFY_WARNING);


        foreach ($results as $ind => $result) {
		$output .= $OUTPUT-> notification('Alumno -'. $ind .'- obtuvo -> ' . $result , \core\output\notification::NOTIFY_WARNING);
}
/*
        foreach ($results as $timesplittingid => $result) {

            if (!CLI_SCRIPT) {
                $output .= $OUTPUT->box_start('generalbox m-b-3');
            }
                $output .= $OUTPUT->heading('Resultado a mostrar por Middle', 4);
                $output .= $OUTPUT->notification(get_string('goodmodel', 'tool_inspire'),
                    \core\output\notification::NOTIFY_WARNING);


            // Check that the array key is a string, not all results depend on time splitting methods (e.g. general errors).
            if (!is_numeric($timesplittingid)) {
                $timesplitting = \tool_inspire\manager::get_time_splitting($timesplittingid);
                $langstrdata = (object)array('name' => $timesplitting->get_name(), 'id' => $timesplittingid);

                if (CLI_SCRIPT) {
                    $output .= $OUTPUT->heading(get_string('executionresultscli', 'tool_inspire', $langstrdata), 3);
                } else {
                    $output .= $OUTPUT->heading(get_string('executionresults', 'tool_inspire', $langstrdata), 3);
                }
            }


            if ($result->status == 0) {
                $output .= $OUTPUT->notification(get_string('goodmodel', 'tool_inspire'),
                    \core\output\notification::NOTIFY_SUCCESS);
            } else if ($result->status === \tool_inspire\model::NO_DATASET) {
                $output .= $OUTPUT->notification(get_string('nodatatoevaluate', 'tool_inspire'),
                    \core\output\notification::NOTIFY_WARNING);
            }

            if (isset($result->score)) {
                // Score.
                $output .= $OUTPUT->heading(get_string('accuracy', 'tool_inspire') . ': ' . round(floatval($result->score), 4) * 100  . '%' . 'por Middle', 4);
            }

            if (!empty($result->info)) {
                foreach ($result->info as $message) {
                    $output .= $OUTPUT->notification($message, \core\output\notification::NOTIFY_WARNING);
                }
            }

            if (!CLI_SCRIPT) {
                $output .= $OUTPUT->box_end();
            }
        }

        // Info logged during execution.
        if (!empty($executionlog) && debugging()) {
            $output .= $OUTPUT->heading(get_string('extrainfo', 'tool_inspire'), 3);
            foreach ($executionlog as $log) {
                $output .= $OUTPUT->notification($log, \core\output\notification::NOTIFY_WARNING);
            }
        }

        if (!CLI_SCRIPT) {
            $output .= $OUTPUT->single_button(new \moodle_url('/admin/tool/inspire/index.php'), get_string('continue'));
        }
*/

        return $output;
    }


    /**
     * Web interface execution results.
     *
     * @param array $trainresults
     * @param string[] $trainlogs
     * @param array $predictresults
     * @param string[] $predictlogs
     * @return string HTML
     */
    public function render_execute_results($trainresults = false, $trainlogs = array(), $predictresults = false, $predictlogs = array()) {
        global $OUTPUT;

        $output = '';

        if ($trainresults || (!empty($trainlogs) && debugging())) {
            $output .= $OUTPUT->heading(get_string('trainingresults', 'tool_inspire'), 3);
        }

        if ($trainresults) {
            if ($trainresults->status == 0) {
                $output .= $OUTPUT->notification(get_string('trainingprocessfinished', 'tool_inspire'),
                    \core\output\notification::NOTIFY_SUCCESS);
            } else if ($trainresults->status === \tool_inspire\model::NO_DATASET) {
                $output .= $OUTPUT->notification(get_string('nodatatotrain', 'tool_inspire'),
                    \core\output\notification::NOTIFY_WARNING);
            } else {
                $output .= $OUTPUT->notification(get_string('generalerror', 'tool_inspire', $result->status),
                    \core\output\notification::NOTIFY_ERROR);
            }
        }

        if (!empty($trainlogs) && debugging()) {
            $output .= $OUTPUT->heading(get_string('extrainfo', 'tool_inspire'), 4);
            foreach ($trainlogs as $log) {
                $output .= $OUTPUT->notification($log, \core\output\notification::NOTIFY_WARNING);
            }
        }

        if ($predictresults || (!empty($predictlogs) && debugging())) {
            $output .= $OUTPUT->heading(get_string('predictionresults', 'tool_inspire'), 3);
        }

        if ($predictresults) {
            if ($predictresults->status == 0) {
                $output .= $OUTPUT->notification(get_string('predictionprocessfinished', 'tool_inspire'),
                    \core\output\notification::NOTIFY_SUCCESS);
            } else if ($predictresults->status === \tool_inspire\model::NO_DATASET) {
                $output .= $OUTPUT->notification(get_string('nodatatopredict', 'tool_inspire'),
                    \core\output\notification::NOTIFY_WARNING);
            } else {
                $output .= $OUTPUT->notification(get_string('generalerror', 'tool_inspire', $result->status),
                    \core\output\notification::NOTIFY_ERROR);
            }
        }

        if (!empty($predictlogs) && debugging()) {
            $output .= $OUTPUT->heading(get_string('extrainfo', 'tool_inspire'), 4);
            foreach ($predictlogs as $log) {
                $output .= $OUTPUT->notification($log, \core\output\notification::NOTIFY_WARNING);
            }
        }

        if (!CLI_SCRIPT) {
            $output .= $OUTPUT->single_button(new \moodle_url('/admin/tool/inspire/index.php'), get_string('continue'));
        }

        return $output;
    }

    /**
     * Model disabled info.
     *
     * @param \stdClass $insightinfo
     * @return string HTML
     */
    public function render_model_disabled($insightinfo) {
        global $OUTPUT, $PAGE;

        // We don't want to disclose the name of the model if it has not been enabled.
        $PAGE->set_title($insightinfo->contextname);
        $PAGE->set_heading($insightinfo->contextname);

        $output = $OUTPUT->header();
        $output .= $OUTPUT->notification(get_string('disabledmodel', 'tool_inspire'), \core\output\notification::NOTIFY_INFO);
        $output .= $OUTPUT->footer();

        return $output;
    }

    /**
     * Model without predictions info.
     *
     * @param \context $context
     * @return string HTML
     */
    public function render_no_predictions(\context $context) {
        global $OUTPUT, $PAGE;

        // We don't want to disclose the name of the model if it has not been enabled.
        $PAGE->set_title($context->get_context_name());
        $PAGE->set_heading($context->get_context_name());

        $output = $OUTPUT->header();
        $output .= $OUTPUT->notification(get_string('nopredictionsyet', 'tool_inspire'), \core\output\notification::NOTIFY_INFO);
        $output .= $OUTPUT->footer();

        return $output;
    }
}
