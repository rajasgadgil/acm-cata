// Generated by CoffeeScript 1.6.3
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  jQuery(function($) {
    var ChooseGalleryView, NextgenEditor, _ref;
    ChooseGalleryView = (function(_super) {
      __extends(ChooseGalleryView, _super);

      function ChooseGalleryView() {
        this.onSelectClick = __bind(this.onSelectClick, this);
        this.onResetClick = __bind(this.onResetClick, this);
        _ref = ChooseGalleryView.__super__.constructor.apply(this, arguments);
        return _ref;
      }

      ChooseGalleryView.prototype.initialize = function(params) {
        ChooseGalleryView.__super__.initialize.call(this, params);
        this.selectGalleryButton = $('#nextgen-select-gallery');
        this.resetGalleryButton = $('#nextgen-reset-gallery');
        this.selectGalleryButton.on('click', this.onSelectClick);
        this.resetGalleryButton.on('click', this.onResetClick);
        this.galleryInput = this.$el.find('#nextgen-gallery');
        this.galleryNameInput = this.$el.find('#nextgen-gallery-name');
        return this.gallerySelector = new window.asg.ExternalGallerySelector;
      };

      ChooseGalleryView.prototype.onResetClick = function(event) {
        event.preventDefault();
        this.model.set('gallery', '');
        return this.model.set('gallery_name', '');
      };

      ChooseGalleryView.prototype.onSelectClick = function(event) {
        var _this = this;
        event.preventDefault();
        this.gallerySelector.select({
          ajax_action: 'asg-nextgen-get-galleries',
          value: this.galleryInput.val(),
          ajax_data: this.model.attributes,
          title: 'Select gallery'
        }).done(function(val) {
          _this.model.set('gallery', val.id);
          return _this.model.set('gallery_name', val.get('title'));
        });
        return false;
      };

      return ChooseGalleryView;

    })(Backbone.View);
    NextgenEditor = (function(_super) {
      __extends(NextgenEditor, _super);

      function NextgenEditor(editor) {
        var _this = this;
        NextgenEditor.__super__.constructor.call(this, editor);
        this.model = new Backbone.Model();
        rivets.bind(editor, {
          model: this.model
        }).publish();
        new ChooseGalleryView({
          el: $('#nextgen-select-gallery-block'),
          model: this.model
        });
        $('#nextgen-settings-block .button').on('click', function() {
          return Preview.show();
        });
      }

      return NextgenEditor;

    })(window.asgSourceEditor);
    return window.asgRegisteredSourceEditors.nextgen = NextgenEditor;
  });

}).call(this);