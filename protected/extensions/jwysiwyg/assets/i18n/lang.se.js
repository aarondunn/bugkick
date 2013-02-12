/**
 * Internationalization: swedish language
 * 
 * Depends on jWYSIWYG, $.wysiwyg.i18n
 *
 * By: ippa@rubylicio.us
 *
 */
(function ($) {
	if (undefined === $.wysiwyg) {
		throw "lang.se.js depends on $.wysiwyg";
	}
	if (undefined === $.wysiwyg.i18n) {
		throw "lang.se.js depends on $.wysiwyg.i18n";
	}

	$.wysiwyg.i18n.lang.se = {
		controls: {
			"Bold": "Tjock",
			"Colorpicker": "",
			"Copy": "Kopiera",
			"Create link": "Skaka länk",
			"Cut": "Klipp",
			"Decrease font size": "Minska storlek",
			"Fullscreen": "",
			"Header 1": "Rubrik 1",
			"Header 2": "Rubrik 2",
			"Header 3": "Rubrik 3",
			"View source code": "Se källkod",
			"Increase font size": "Öka fontstorlek",
			"Indent": "Öka indrag",
			"Insert Horizontal Rule": "Lägg in vertical avskiljare ",
			"Insert image": "Infoga bild",
			"Insert Ordered List": "Infoga numrerad lista",
			"Insert table": "Infoga tabell",
			"Insert Unordered List": "Infoga lista",
			"Italic": "Kursiv",
			"Justify Center": "Centrera",
			"Justify Full": "Marginaljustera",
			"Justify Left": "Vänsterjustera",
			"Justify Right": "Högerjustera",
			"Left to Right": "Vänster till höger",
			"Outdent": "Minska indrag",
			"Paste": "Klistra",
			"Redo": "Gör om",
			"Remove formatting": "Ta bort formatering",
			"Right to Left": "Höger till vänster",
			"Strike-through": "Genomstrykning",
			"Subscript": "Subscript",
			"Superscript": "Superscript",
			"Underline": "Understruken",
			"Undo": "Ångra"
		},

		dialogs: {
			// for all
			"Apply": "",
			"Cancel": "",

			colorpicker: {
				"Colorpicker": "",
				"Color": ""
			},

			image: {
				"Insert Image": "",
				"Preview": "",
				"URL": "",
				"Title": "",
				"Description": "",
				"Width": "",
				"Height": "",
				"Original W x H": "",
				"Float": "",
				"None": "",
				"Left": "",
				"Right": ""
			},

			link: {
				"Insert Link": "",
				"Link URL": "",
				"Link Title": "",
				"Link Target": ""
			},

			table: {
				"Insert table": "",
				"Count of columns": "",
				"Count of rows": ""
			}
		}
	};
})(jQuery);
