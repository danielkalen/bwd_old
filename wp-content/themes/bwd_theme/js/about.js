$$('.gallery-item').on('click', function(){
	$this = $(this);
	$thisLink = $this.find('img').attr('src').replace('-150x150', '');

	window.location = $thisLink;
});