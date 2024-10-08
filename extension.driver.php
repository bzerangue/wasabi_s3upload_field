<?php

	require_once(TOOLKIT . '/class.entrymanager.php');

	Class extension_wasabi_s3upload_field extends Extension {

		public function install() {
			return Symphony::Database()->query("CREATE TABLE `tbl_fields_wasabi_s3upload` (
					`id` int(11) unsigned NOT NULL auto_increment,
					`field_id` int(11) unsigned NOT NULL,
					`bucket` varchar(255) NOT NULL,
					`cname` varchar(255),
					`remove_from_bucket` tinyint(1) DEFAULT '1',
					`unique_filename` tinyint(1) DEFAULT '1',
					`ssl_option` tinyint(1) DEFAULT '0',
					`validator` varchar(255),
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				) ENGINE=MyISAM	 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
			);
		}

		public function uninstall() {
			Symphony::Database()->query("DROP TABLE `tbl_fields_wasabi_s3upload`");
			Symphony::Configuration()->remove('wasabi_s3upload_field');

			return Symphony::Configuration()->write();
		}

		public function update($previousVersion = false) {
			if(version_compare($previousVersion, '0.6.4', '<')) {
				// Add new row:
				Symphony::Database()->query(
					"ALTER TABLE `tbl_fields_wasabi_s3upload` ADD `unique_filename` tinyint(1) DEFAULT '1'"
				);
				Symphony::Database()->query(
					"ALTER TABLE `tbl_fields_wasabi_s3upload` ADD `ssl_option` tinyint(1) DEFAULT '0'"
				);
			}
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/system/preferences/',
					'delegate' => 'CustomActions',
					'callback' => 'savePreferences'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendPreferences'
				),
			);
		}

		public function appendPreferences($context){
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', 'Wasabi S3 Security Credentials'));

			$div = new XMLElement('div', NULL, array('class' => 'two columns'));

			$label = Widget::Label('Access Key ID');
            $label->setAttribute('class', 'column');
			$label->appendChild(Widget::Input('settings[wasabi_s3upload_field][access-key-id]', General::Sanitize($this->getAmazonS3AccessKeyId())));
			$div->appendChild($label);

			$label = Widget::Label('Secret Access Key');
            $label->setAttribute('class', 'column');
			$label->appendChild(Widget::Input('settings[wasabi_s3upload_field][secret-access-key]', General::Sanitize($this->getAmazonS3SecretAccessKey()), 'password'));
			$div->appendChild($label);

			$group->appendChild($div);
			$group->appendChild(new XMLElement('p', 'Get a Access Key ID and Secret Access Key from the <a href="http://console.wasabisys.com">Wasabi S3 Console</a>.', array('class' => 'help')));

			$label = Widget::Label('Default cache expiry time (in seconds)');
			$label->appendChild(Widget::Input('settings[wasabi_s3upload_field][cache-control]', General::Sanitize($this->getCacheControl())));

			$group->appendChild($label);
			$context['wrapper']->appendChild($group);
		}

		public function getCacheControl() {
			$val = Symphony::Configuration()->get('cache-control', 'wasabi_s3upload_field');
			if (!preg_match('/^[\d]+$/', $val) && $val != '') return '864000';
			elseif ($val == '') return false;
			else return $val;
		}

		public function getAmazonS3AccessKeyId(){
			return Symphony::Configuration()->get('access-key-id', 'wasabi_s3upload_field');
		}

		public function getAmazonS3SecretAccessKey(){
			return Symphony::Configuration()->get('secret-access-key', 'wasabi_s3upload_field');
		}
	}
