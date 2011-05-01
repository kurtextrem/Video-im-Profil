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
		$value = trim($value);

		if (!empty($value)) {
			StringUtil::encodeHTML($value);
			$parsed_url = parse_url($value);
			$return = '<div id="video">';

			if(empty($parsed_url['query']))
				$parsed_url['query'] = '';
			if (!isset($parsed_url['host'])){
				$host = '';
			}else{
				$host = str_replace('www.', '', $parsed_url['host']);
			}

			preg_match_all('/;([^;]+)/', $parsed_url['path'].$parsed_url['query'], $matches);
			$replace = array('hd' => 'hd=1', 'autoplay' => 'autplay=1', 'loop' => 'loop=1');

			switch ($host) {
				case 'youtube.com':
					preg_match('/v=([^&#;]+)/', $parsed_url['query'], $matches);
					$return .= '<object type="application/x-shockwave-flash" style="width:425px; height:349px" data="//www.youtube.com/v/'.$matches.'?'.$flags.'"><param name="movie" value="//www.youtube.com/v/'.$matches.'?'.$flags.'" /><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param></object>';
					break;

				case 'vimeo.com':
					$path = str_replace('/', '', $parsed_url['path']);
					$return .= '<object width="400" height="225"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$path.$flags.'" /></object>';
					break;

				case 'myvideo.de':
					preg_match('~/watch/(.*)/.*~', $parsed_url['path'], $matches);
					$return .= '<object style="width:425px;height:350px;" width="425" height="350"><param name="movie" value="http://www.myvideo.de/movie/'.$value.'"></param><param name="AllowFullscreen" value="true"></param><param name="AllowScriptAccess" value="always"></param><embed src="http://www.myvideo.de/movie/'.$value.'" width="425" height="350" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
					break;

				default:
					return '<object type="application/x-shockwave-flash" style="width:425px; height:349px" data="http://www.youtube.com/v/DD0A2plMSVA"><param name="movie" value="http://www.youtube.com/v/DD0A2plMSVA" /><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param></object>';
			}

			return $return + '</div>';
		}
	}

}

?>