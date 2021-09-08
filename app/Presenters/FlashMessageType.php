<?php declare(strict_types = 1);

namespace Mrcek\BraintreeTest\Presenters;


use MyCLabs\Enum\Enum;

/**
 * @extends  Enum<string>
 */
class FlashMessageType extends Enum {

	public const
		PRIMARY = "primary",
		SECONDARY = "secondary",
		SUCCESS = "success",
		ERROR = "danger",
		WARNING = "warning",
		INFO = "info",
		LIGHT = "light",
		DARK = "dark";
}