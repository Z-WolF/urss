<?php
class Toggle_Sidebar extends Plugin {

	private $host;

	public function about() {
		return array(1.0,
			"Adds a main toolbar button to toggle sidebar",
			"fox");
	}

	public function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_MAIN_TOOLBAR_BUTTON, $this);
	}

	public function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	public function hook_main_toolbar_button() {
		?>

		<button dojoType="dijit.form.Button" onclick="Plugins.Toggle_Sidebar.toggle(this)">
			<i class="material-icons toggle-sidebar-label"
               title="<?php echo __('Toggle sidebar') ?>">chevron_left</i>
		</button>

		<?php
	}

	public function api_version() {
		return 2;
	}

}
?>
