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
			if (empty($parsed_url['query']))
				$parsed_url['query'] = '';
			$host = 'www.'.str_replace('www.', '', $parsed_url['host']); // i do it so, cause sometime there is a www. url so i need to replace it first.
			// parse ;XYZ
			preg_match_all('/;([^;]+)/', $parsed_url['path'].$parsed_url['query'], $flags);
			if (!empty($flags[1][0])) {
				$flags = implode('&amp;', $flags[1]);
				$replace = array(
					'hd' => 'hd=1',
					'rel' => 'rel=1',
					'autoplay' => 'autoplay=1',
					'loop' => 'loop=1',
					'&amp;preview' => '',
					'&amp;title' => '',
					'&amp;by' => ''
				);
				foreach ($replace as $replace => $with) {
					$flags = str_replace($replace, $with, $flags);
				}
			} else {
				$flags = '';
			}

			// create div for styling
			$return = '<div id="video">';
			// check which provider
			switch ($host) {
				case 'www.youtube.com':
					$flags .= '&amp;rel=0';
					preg_match('/v=([^&#;]+)/', $parsed_url['query'], $matches); // extract id
					$return .= '<object style="width:425px; height:349px" data="//www.youtube.com/v/'.$matches[1].$flags.'"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /></object>';
					break;

				case 'www.vimeo.com':
					$flags .= '&amp;show_portrait=0&amp;show_title=0&amp;show_byline=0';
					$path = str_replace('/', '', $parsed_url['path']); // extract id
					$return .= '<object style="width:500px; height:349px;"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$path.'&amp;'.$flags.'" /></object>';
					break;

				case 'www.myvideo.de':
					preg_match('~/watch/(.*)/.*~', $parsed_url['path'], $matches); // extract id
					$return .= '<object style="width:500px; height:349px;" data="http://www.myvideo.de/movie/'.$matches[1].'"><param name="movie" value="http://www.myvideo.de/movie/'.$matches[1].'" /><param name="AllowFullscreen" value="true" /><param name="AllowScriptAccess" value="always" /></object>';
					break;

				default:
					// if there is no valid provider
					$return .= '<object style="width:425px; height:349px" data="//www.youtube.com/v/DD0A2plMSVA"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /></object>';
					break;
			}

			// finish it
			return $return.'</div>';
		}
	}

}

?>