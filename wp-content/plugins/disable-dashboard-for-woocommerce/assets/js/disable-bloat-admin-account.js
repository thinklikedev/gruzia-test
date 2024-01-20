jQuery(document).ready(function ($) {
    var wcBloatUpgradeLink = $('a[href*="disable-dashboard-for-woocommerce-pricing"]');
    wcBloatUpgradeLink.hide();
    wcBloatUpgradeLink.each(function () {
        var bullet = $(this).parent().prev('li');
        if (bullet.html().includes('&nbsp;•&nbsp;')) {
            bullet.hide();
        }
    });
});