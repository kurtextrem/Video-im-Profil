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

			switch ($parsed_url['host']) {
				case 'youtube.com':
					break;

				case 'vimeo.com':
					break;

				case 'clipfish.de':
					break;

				case 'myvideo.de':
					break;

				default:
					return '<div id="color: red">Error! Unknown URL</div>';
			}
		}
	}

}

?>