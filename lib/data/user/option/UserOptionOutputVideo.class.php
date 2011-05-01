<?php

require_once(WCF_DIR.'lib/data/user/option/UserOptionOutput.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * @author  kurtextrem <kurtextrem@gmail.com>
 * @package com.kurtextrem.useroption.video
 * @license CC BY-SA <http://creativecommons.org/licenses/by-sa/3.0/>
 */
class UserOptionOutputVideo implements UserOptionOutput {

	/**
	 * @see UserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, $optionData, $value) {
		return $this->getOutput($user, $optionData, $value);
	}

	/**
	 * @see UserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, $optionData, $value) {
		return $this->getOutput($user, $optionData, $value);
	}

	/**
	 * @see UserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, $optionData, $value) {
		if ($value != "") {
			StringUtil::encodeHTML($value);
			$value = trim($value);
			$slen = strlen($value);
			$addi = '';
			if (stristr($value, ";html5") === false) {
				if ($slen <= 15) {
					if (stristr($value, ";hd") !== false) {
						$value = str_replace(';hd', '', $value);
						$addi .= "&amp;hd=1";
					}
					if (stristr($value, ";color1") !== false || stristr($value, ";color2") !== false) {
						preg_match_all('/;color[1-2]=[A-Za-z0-9]*/', $value, $match);
						$addi .= "&amp".$match[0][0]."&amp".$match[0][1];
						$value = preg_replace('/;color[1-2]=[A-Za-z0-9]*/', '', $value);
					}
					return '<div id="youtubevideo"><object type="application/x-shockwave-flash" style="width:425px; height:350px" data="http://www.youtube.com/v/'.$value.'?rel=0'.$addi.'"><param name="movie" value="http://www.youtube.com/v/'.$value.'?rel=0'.$addi.'" /></object></div>';
				} else {
					if (stristr($value, ";hd") !== false) {
						$value = str_replace(';hd', '', $value);
						$addi .= "&amp;hd=1";
					}
					if (stristr($value, ";color1") !== false || stristr($value, ";color2") !== false) {
						preg_match_all('/;color[1-2]=[A-Za-z0-9]*/', $value, $match);
						$addi .= "&amp".$match[0][0]."&amp".$match[0][1];
						$value = preg_replace('/;color[1-2]=[A-Za-z0-9]*/', '', $value);
					}
					$value = str_replace('watch?v=', 'v/', $value);
					return '<div id="youtubevideo"><object type="application/x-shockwave-flash" style="width:425px; height:350px" data="'.$value.'?rel=0'.$addi.'"><param name="movie" value="'.$value.'?rel=0'.$addi.'" /></object></div>';
				}
			} else {
				// Dont kill me for that... this is the only "real" embed method 4 html5 videos from youtube. q.q
				if ($slen <= 15) {
					$value = str_replace(';html5', '', $value);
					$value = str_replace(';hd', '', $value);
					$value = preg_replace('/;color[1-2]=[A-Za-z0-9]*/', '', $value);
					if (stristr($value, ";hd") !== false) {
						$value = str_replace(';hd', '', $value);
						$addi .= "&amp;hd=1";
					}
					return '<div id="youtubevideo"><iframe class="youtube-player" style="width:960px;height:750px;border:0px" src="http://www.youtube.com/embed/'.$value.'?rel=0'.$addi.'"></iframe></div>';
				} else {
					$value = str_replace('watch?v=', 'embed/', $value);
					$value = str_replace(';html5', '', $value);
					$value = preg_replace('/;color[1-2]=[A-Za-z0-9]*/', '', $value);
					if (stristr($value, ";hd") !== false) {
						$value = str_replace(';hd', '', $value);
						$addi .= "&amp;hd=1";
					}
					return '<div id="youtubevideo"><iframe class="youtube-player" style="width: 425px;height: 350px;border:0px" src="'.$value.'?rel=0'.$addi.'"></iframe></div>';
				}
			}
		}
	}

}

?>