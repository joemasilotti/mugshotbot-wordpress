(function(wp) {
  var PluginSidebar = wp.editPost.PluginSidebar;
  var Text = wp.components.TextControl;
  var compose = wp.compose.compose;
  var el = wp.element.createElement;
  var registerPlugin = wp.plugins.registerPlugin;
  var withDispatch = wp.data.withDispatch;
  var withSelect = wp.data.withSelect;

  var MetaBlockField = compose(
    withDispatch(function(dispatch, props) {
      return {
        setMetaFieldValue: function(value) {
          dispatch('core/editor').editPost(
            { meta: { [props.fieldName]: value } }
          );
        }
      }
    } ),
    withSelect(function(select, props) {
      return {
        metaFieldValue: select('core/editor')
        .getEditedPostAttribute('meta')
        [props.fieldName],
      }
    } )
  )(function(props) {
    return el(Text, {
      label: 'Custom image URL for Two Up theme',
      value: props.metaFieldValue,
      onChange: function(content) {
        props.setMetaFieldValue(content);
      },
    } );
  } );

  registerPlugin('mugshot-bot', {
    render: function() {
      return el(PluginSidebar,
        {
          name: 'mugshot-bot',
          icon: 'admin-post',
          title: 'Mugshot Bot',
        },
        el('div',
          { className: 'plugin-sidebar-content p-4' },
          el(MetaBlockField,
            { fieldName: 'mugshot_bot_image_url' }
          )
        )
      );
    }
  } );
})(window.wp);
