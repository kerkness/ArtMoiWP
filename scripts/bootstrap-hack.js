var bootstrapCss = 'bootstrapCss';
var customStylesCss = 'customStylesCss';

if (!document.getElementById(bootstrapCss))
{
    var head = document.getElementsByTagName('head')[0];

    var bootstrapWrapper = document.createElement('link');
    bootstrapWrapper.id = bootstrapCss;
    bootstrapWrapper.rel = 'stylesheet/less';
    bootstrapWrapper.type = 'text/css';
    bootstrapWrapper.href = '../wp-content/plugins/ArtMoiWP/css/bootstrap-wrapper.less';
    bootstrapWrapper.media = 'all';
    head.appendChild(bootstrapWrapper);

    //load other stylesheets that override bootstrap styles here, using the same technique from above
    //var customStyles = document.createElement('link');
    //customStyles.id = customStylesCss;
    //customStyles.rel = 'stylesheet';
    //customStyles.type = 'text/css';
    //customStyles.href = '../wp-content/plugins/ArtMoiWP/css/wp-artmoi-style.css';
    //customStyles.media = 'all';
    //head.appendChild(customStyles);

    var lessjs = document.createElement('script');
    lessjs.type = 'text/javascript';
    lessjs.src = '../wp-content/plugins/ArtMoiWP/scripts/less.min.js';
    head.appendChild(lessjs);


}

