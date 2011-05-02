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

			if (empty($parsed_url['query']))
				$parsed_url['query'] = '';
			$host = 'www.'.str_replace('www.', '', $parsed_url['host']); // i do it so, cause sometime there is a www. url so i need to replace it first.

			preg_match_all('/;([^;]+)/', $parsed_url['path'].$parsed_url['query'], $flags);
			if (!empty($matches[1][0])) {
				$flags = implode('&', $flags);
				$replace = array('hd' => 'hd=1', 'autoplay' => 'autplay=1', 'loop' => 'loop=1');
				foreach ($replace as $replace => $with) {
					$flags = str_replace($replace, $with, $flags);
				}
			} else {
				$flags = '';
			}

			switch ($host) {
				case 'www.youtube.com':
					preg_match('/v=([^&#;]+)/', $parsed_url['query'], $matches);
					$return .= '<object style="width:425px; height:349px" data="//www.youtube.com/v/'.$matches[1].'?f=b'.$flags.'"><param name="movie" value="//www.youtube.com/v/'.$matches[1].'?f=b'.$flags.'" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /></object>';
					break;

				case 'www.vimeo.com':
					$path = str_replace('/', '', $parsed_url['path']);
					$return .= '<object style="width:500px; height:349px;"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$path.$flags.'" /></object>';
					break;

				case 'www.myvideo.de':
					preg_match('~/watch/(.*)/.*~', $parsed_url['path'], $matches);
					$return .= '<object style="width:500px; height:349px;"><embed src="http://www.myvideo.de/movie/'.$matches[1].'" style="width:500px;height:349px;" /><param name="AllowFullscreen" value="true" /><param name="AllowScriptAccess" value="always" /></object>';
					break;

				default:
					$return .= '<object style="width:425px; height:349px" data="//www.youtube.com/v/DD0A2plMSVA"><param name="movie" value="//www.youtube.com/v/DD0A2plMSVA" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /></object>';
					break;
			}

			return $return.'</div>';
		}
	}

}

?>