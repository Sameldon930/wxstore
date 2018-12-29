Vue.component("no-data", {
    template: '\
        <div class="no-data">\
            <img src="i/no-data.png" class="no-data-img">\
            <div class="wis-text-gray am-padding-top-xs am-text-sm">{{ data.text }}</div>\
        </div>\
        ',
    props: {
    },
    data: function () {
        console.log(this.$parent)
        var data = this.$parent.component_no_data || {text: "暂没有记录"}
        return {
            component_no_data: {},
            data: data
        }
    },
    methods: {
    }
});