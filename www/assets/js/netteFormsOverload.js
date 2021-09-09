"use strict";

var Mrcek = Mrcek || {};

Mrcek.validate = {
	classParent:      "app-form-group",
	classParentAlert: "has-danger",
	dataTargetName:   "error-message-target",
	scrollEnable:     true,
	isScrolling:      false
};

/**
 * Display error message.
 */
Nette.addError = function (elem, message) {
	if (message) {
		Mrcek.showError(elem, message);
		Nette.formErrors.push({
								  element: elem,
								  message: message
							  });
	}
};

/**
 * Display error messages.
 */
Nette.showFormErrors = function (form, errors) {
	var netteErrors = [];

	for (var i = 0; i < errors.length; i++) {
		if (!Mrcek.showError(errors[i].element, errors[i].message)) {
			netteErrors.push(errors[i]);
		}
	}


	var messages = [],
		focusElem;

	for (var i = 0; i < netteErrors.length; i++) {
		var elem = netteErrors[i].element,
			message = netteErrors[i].message;

		if (messages.indexOf(message) < 0) {
			messages.push(message);

			if (!focusElem && elem.focus) {
				focusElem = elem;
			}
		}
	}

	if (messages.length) {
		alert(messages.join('\n'));

		if (focusElem) {
			focusElem.focus();
		}
	}
};

/**
 * Setup handlers.
 */
Nette.initForm = function (form) {
	Nette.toggleForm(form);

	if (form.noValidate) {
		return;
	}

	form.noValidate = true;

	form.addEventListener('submit', function (e) {
		if (!Nette.validateForm(form)) {
			e.stopPropagation();
			e.preventDefault();
		}
	});

	Mrcek.registerValidateElementsListeners(form);
};

/**
 * Register validate listener on form element
 * @param formElem
 */
Mrcek.registerValidateElementsListeners = function (formElem) {
	var form = formElem.form || formElem,
		scope = false;

	Nette.formErrors = [];

	if (form['nette-submittedBy'] && form['nette-submittedBy'].getAttribute('formnovalidate') !== null) {
		var scopeArr = JSON.parse(form['nette-submittedBy'].getAttribute('data-nette-validation-scope') || '[]');
		if (scopeArr.length) {
			scope = new RegExp('^(' + scopeArr.join('-|') + '-)');
		}
		else {
			Nette.showFormErrors(form, []);
			return true;
		}
	}

	var radios = {}, i, elem;

	for (i = 0; i < form.elements.length; i++) {
		elem = form.elements[i];

		if (elem.tagName && !(elem.tagName.toLowerCase() in {input: 1, select: 1, textarea: 1})) {
			continue;

		}
		else if (elem.type === 'radio') {
			if (radios[elem.name]) {
				continue;
			}
			radios[elem.name] = true;
		}

		if ((scope && !elem.name.replace(/]\[|\[|]|$/g, '-').match(scope)) || Nette.isDisabled(elem)) {
			continue;
		}

		if (elem.type === 'radio') {
			$("input[type=radio][name='" + elem.name + "']").change(
				function () {
					var element = $(this);
					Mrcek.callValidateInput(element);
				}
			);
		}
		else if (elem.type === 'checkbox') {
			$(elem).change(
				function () {
					var element = $(this);
					Mrcek.callValidateInput(element);
				}
			);
		}
		else {
			$(elem).focusout(
				function () {
					var element = $(this);
					Mrcek.callValidateInput(element);
				}
			);
		}
	}
};

/**
 * Call validate control function and set class
 * @param element
 */
Mrcek.callValidateInput = function (element) {
	var parentFormGroup = element.closest("." + Mrcek.validate.classParent);
	parentFormGroup.removeClass(Mrcek.validate.classParentAlert);
	$(element.data(Mrcek.validate.dataTargetName)).text('').hide();

	Nette.validateControl(element);
};

Mrcek.showError = function (el, message) {
	var element = $(el);

	var parentFormGroup = element.closest("." + Mrcek.validate.classParent);
	parentFormGroup.addClass(Mrcek.validate.classParentAlert);

	if (element.data(Mrcek.validate.dataTargetName)) {
		$(element.data(Mrcek.validate.dataTargetName)).text(message).fadeIn();

		if (Mrcek.validate.scrollEnable && !Mrcek.validate.isScrolling) {
			Mrcek.validate.isScrolling = true;
			Mrcek.scrollTo(parentFormGroup, -100, 600, function () {
				Mrcek.validate.isScrolling = false;
			});
		}

		return true;
	}

	return false;
};

Mrcek.scrollTo = function (target, offset, speed, completeListener) {
	if (!(target instanceof jQuery)) {
		target = $(target);
	}

	offset = (typeof offset !== 'undefined') ? offset : 0;
	speed = (typeof speed !== 'undefined') ? speed : 500;

	var body = $('body');
	if (body.attr('data-scroll-offset')) {
		offset += body.data('scrollOffset');
	}

	if (target.length) {
		$('html, body').stop().animate(
			{
				scrollTop: target.offset().top + offset
			},
			speed,
			function () {
				if (completeListener === 'undefined') {
					return;
				}

				completeListener();
			}
		);
	}
};

Mrcek.validators = {
	SurfaceUtilsFormValidator_phoneNumber: function (elem, args, val) {
		var pattern = new RegExp("^(\\+[0-9]{3}[ ]?)?[0-9]{3}[ ]?[0-9]{3}[ ]?[0-9]{3}$|^(\\+[0-9]{3})?[0-9]{9}$");
		return pattern.test(val);
	},
	SurfaceUtilsFormValidator_postCode:    function (elem, args, val) {
		var pattern = new RegExp("^[0-9]{3}[ ]?[0-9]{2}$");
		return pattern.test(val);
	}
};

Nette.validators = $.extend(Nette.validators, Mrcek.validators);