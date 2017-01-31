jQuery(document).ready(function($) {
	
	$("#genesis-theme-settings-nav input:radio:checked").live('load change', function() {
		
		if ( $(this).val() == 'nav-menu' ) {
			$("#genesis-theme-settings-nav .nav-opts").hide('fast');
		}
		
		if ( $(this).val() != 'nav-menu' ) {
			$("#genesis-theme-settings-nav .nav-opts").show('fast');
		}
		
	});
	
	$("#genesis-theme-settings-subnav input:radio:checked").live('load change', function() {
		
		if ( $(this).val() == 'nav-menu' ) {
			$("#genesis-theme-settings-subnav .nav-opts").hide('fast');
		}
		
		if ( $(this).val() != 'nav-menu' ) {
			$("#genesis-theme-settings-subnav .nav-opts").show('fast');
		}
		
	});
	
});

function genesis_confirm( text ) {
	var answer = confirm( text );
	
	if( answer ) { return true; }
	else { return false; }
}