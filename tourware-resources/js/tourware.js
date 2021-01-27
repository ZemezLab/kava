Vue.component('travel-gallery', {
    template: '<h1>Pauli Bobby</h1>',
    props: ['config'],
    mounted: function () {
        console.log(JSON.parse(this.$attrs['data-config']))
        console.log(JSON.parse(this.$attrs['data-record']))
    }
})

jQuery( function( $ ) {
    if ( window.elementorFrontend ) {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
            console.log($scope)
            // new Vue({
            //     el: $scope.find('.vue-widget-wrapper')[0]
            // });
        } );
    }
} );