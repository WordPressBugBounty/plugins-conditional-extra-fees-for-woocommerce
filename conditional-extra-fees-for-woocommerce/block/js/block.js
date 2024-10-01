
const OptionalFeesComponent = ({ cart, extensions }) => {


    const [options, setOptions] = React.useState(extensions.pisol_cefw_fees.options);

    React.useEffect(() => {
        setOptions(extensions.pisol_cefw_fees.options);
    }, [extensions.pisol_cefw_fees.options]);

    const handleCheckboxChange = (id, event) => {

        setOptions(options.map(item => {
            if (item.id === id) {
                item.checked = event.target.checked;
            }
            return item;
        }));
        
        if (event.target.checked) {
            var data = { id: id, checked: true };
        } else {
            var data = { id: id, checked: false };
        }

        document.body.classList.add('pi-cefw-processing');
        wc.blocksCheckout.extensionCartUpdate({
            namespace: 'pisol_cefw_fees',
            data: data
        }).then(() => {
            document.body.classList.remove('pi-cefw-processing');
        });
    };

    if (typeof extensions.pisol_cefw_fees == 'undefined') return [];

    if (options.length == 0) return [];

    return React.createElement("div", {
        className: 'pisol-fees-container'
    }, React.createElement("strong", {
        className: 'pisol-fees-container-label'
    }, extensions.pisol_cefw_fees.label), options.map(item => React.createElement("div", {
        key: item.id,
        className: 'pisol-fees-parent'
    }, React.createElement("label", {
        htmlFor: item.id,
    }, React.createElement("input", {
        type: "checkbox",
        id: item.id,
        onChange: (e) => handleCheckboxChange(item.id, e),
        checked: item.checked
    }), item.title))));

};

const render = () => {
    return React.createElement(wc.blocksCheckout.ExperimentalOrderMeta, null, React.createElement(OptionalFeesComponent, null));
};

wp.plugins.registerPlugin('pisol-cefw-fees', {
    render,
    scope: 'woocommerce-checkout',
});