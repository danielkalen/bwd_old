var sourceTitleParentId = document.getElementById('woocommerce_widget_cart-2')
var sourceTitleParent = document.getElementsByClassName('mini-cart-item-dk');
var sourceTitleHtml = sourceTitleParent.getElementsByTagName('a');
var sourceTitle = sourceTitleHtml.innerHtml;

function cutTitle() {
	if (sourceTitle.length > 14) { 
	var newTitle = sourceTitle.substr(0,14) + '...';
	sourceTitle.innerHtml = newTitle;
	} 
	else{
	var oldTitle = sourceTitle;
	sourceTitle.innerHtml = oldTitle;
	}
}

cutTitle();

