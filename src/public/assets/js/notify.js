const notifySetting = {
    // settings
    element: 'body',
    style: "alert",
    position: null,
    type: "info",
    allow_dismiss: true,
    newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 10,
    spacing: 10,
    z_index: 1040,
    delay: 3300,
    timer: 1000,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animate__animated animate__bounceInDown',
        exit: 'animate__animated animate__fadeOutUp'
        // exit: 'animated bounceOutUp'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
}

const calloutTemplateSetting = {
    type:"primary",
    size:"xl",
    transparent: true,
    disabled: false
}

const calloutTemplate = (template={}) => {
    // default|primary|success|danger|warning|info
    const type = (template.type != undefined) ? template.type : "default";
    // xl|lg|md|sm|xs
    const size = (template.size != undefined) ? template.size : "md";
    // true|false
    const transparent = (template.transparent != undefined && template.transparent == true) ? "transparent" : "";
    // true|false
    const disabled = (template.disabled != undefined && template.disabled == true) ? "disabled" : "default";
    return `
        <div class="bs-callout bs-callout-${type} bs-callout-${size} ${transparent} ${disabled} shadow bg-body rounded col-10 col-sm-6 col-md-4 col-lg-3">
            <h4 data-notify="title">{1}</h4>
            {2}
        </div>
    `;
}

const callout = (title="Info", message="", template={}, additional={}) => {
    var msg = {
        title: title,
        message: message,
    };

    template = replaceObject(template, calloutTemplateSetting);
    additional.template = calloutTemplate(template);

    let settings = replaceObject(additional, notifySetting);

    $.notify(msg, settings);
}

/* OLD VERSION */
const notifyAlertTemplate = () => {
    return `
        <div data-notify="container" class="col-10 col-sm-6 col-md-4 col-lg-3 alert alert-{0} alert-dismissible" role="alert">
            <button type="button" class="close" data-notify="dismiss" aria-hidden="true">&times;</button>
            <h5 class="d-flex align-items-stretch">
                <i data-notify="icon" class="align-self-center"></i>
                <span data-notify="title" class="align-self-center">{1}</span>
            </h5>
            {2}
        </div>
    `;
};

const notifyAlertToastTemplate = () => {
    return `
        <div data-notify="container" class="col-10 col-sm-6 col-md-4 col-lg-3 alert alert-{0} alert-dismissible" role="alert">
            <button type="button" class="close" data-notify="dismiss" aria-hidden="true">&times;</button>
            <h6 class="d-flex align-items-stretch my-0">
                <i data-notify="icon" class="align-self-center"></i>
                <span data-notify="message" class="align-self-center">{2}</span>
            </h6>
        </div>
    `;
};

const notifyCalloutTemplate = () => {
    return `
        <div data-notify="container" class="col-10 col-sm-6 col-md-4 col-lg-3 shadow bg-body rounded callout callout-{0}">
            <h5 data-notify="title">{1}</h5>
            <p data-notify="message">{2}</p>
        </div>
    `;
}

const notify = (title="Info", message="", style="alert", additional={}) => {
    var msg = {
        title: title,
        message: message,
        icon: "icon fas fa-info"
    };
    additional.template = notifyAlertTemplate();
    if(style == "alert-toast") { additional.template = notifyAlertToastTemplate(); }
    if(style == "callout") { additional.template = notifyCalloutTemplate(); }

    if(additional.type == undefined) { additional.type = "dark"; }

    if(additional.type == "success") { msg.icon = "icon fas fa-check"; }
    if(additional.type == "warning") { msg.icon = "icon fas fa-exclamation-triangle"; }
    if(additional.type == "danger") { msg.icon = "icon fas fa-ban"; }
    if(additional.icon != undefined) { msg.icon = additional.icon; }

    var settings = replaceObject(additional, notifySetting);

    $.notify(msg, settings);
}
