<style>
    body, html {
        height: 100%;
        margin: 0;
    }

</style>
<div id="gjs" style="height:0px; overflow:hidden;"></div>
<script type="text/javascript">
    // Set up GrapesJS editor with the Newsletter plugin
    var bodytext = '';
    var m = (window.opener.mQuery('textarea.builder-html').val()).match(/<body[^>]*>([^<]*(?:(?!<\/?body)<[^<]*)*)<\/body\s*>/i);
    if (m) {
        bodytext = m[1];
    }
    var editor = grapesjs.init({
        height: '100%',
        noticeOnUnload: 0,
        storageManager: {type: null},
        container: '#gjs',
        components: bodytext,

        assetManager: {
            assets: <?php echo json_encode($images); ?>,
            upload: '<?php echo $view['router']->generate('mautic_grapejs_upload', [], true) ?>',
            uploadName: 'files',
            multiUpload: true,
            // Text on upload input
            uploadText: 'Drop files here or click to upload',
            // Label for the add button
            addBtnText: 'Add image',
            // Default title for the asset manager modal
            modalTitle: 'Select Image',
        },

        plugins: ['gjs-plugin-ckeditor', 'grapesjs-parser-postcss', 'gjs-preset-newsletter'],
        pluginsOpts: {
            'gjs-plugin-ckeditor': {
                position: 'center',
                options: {
                    startupFocus: true,
                    extraAllowedContent: '*(*);*{*}', // Allows any class and any inline style
                    allowedContent: true, // Disable auto-formatting, class removing, etc.
                    enterMode: CKEDITOR.ENTER_BR,
                    extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
                    toolbar: [
                        { name: 'styles', items: ['Font', 'FontSize' ] },
                        ['Bold', 'Italic', 'Underline', 'Strike'],
                        {name: 'paragraph', items : [ 'NumberedList', 'BulletedList']},
                        {name: 'links', items: ['Link', 'Unlink']},
                        {name: 'colors', items: [ 'TextColor', 'BGColor' ]},
                    ],
                }
            },
            'gjs-preset-newsletter': {
                modalLabelImport: 'Paste all your code here below and click import',
                modalLabelExport: 'Copy the code and use it wherever you want',
                codeViewerTheme: 'material',
                //defaultTemplate: templateImport,
                importPlaceholder: '',
                cellStyle: {
                    'font-size': '12px',
                    'font-weight': 300,
                    'vertical-align': 'top',
                    color: 'rgb(111, 119, 125)',
                    margin: 0,
                    padding: 0,
                }
            }
        },
    });

    editor.on('load',
        function() {
            var PLACEHOLDERS = [{
                id: 1,
                name: 'address',
                title: '{Address}',
                description: 'Customer Support correspondence address.'
            },
                {
                    id: 2,
                    name: 'assignee',
                    title: 'Assignee Name',
                    description: 'Ticket assignee name.'
                },
                {
                    id: 3,
                    name: 'deadline',
                    title: 'Deadline Time',
                    description: 'Utmost time to which technician should handle the issue.'
                },
                {
                    id: 4,
                    name: 'department',
                    title: 'Department Name',
                    description: 'Department name responsible for servicing this ticket.'
                },
                {
                    id: 5,
                    name: 'caseid',
                    title: 'Case ID',
                    description: 'Unique case number used to distinguish tickets.'
                },
                {
                    id: 6,
                    name: 'casename',
                    title: 'Case Name',
                    description: 'Name of the ticket provided by the user.'
                },
                {
                    id: 7,
                    name: 'contact',
                    title: 'Contact E-mail',
                    description: 'Customer Support contact e-mail address.'
                },
                {
                    id: 8,
                    name: 'customer',
                    title: 'Customer Name',
                    description: 'Receipent of your response.'
                },
                {
                    id: 9,
                    name: 'hotline',
                    title: 'Hotline Number',
                    description: 'Customer Support Hotline number.'
                },
                {
                    id: 10,
                    name: 'technician',
                    title: 'Technician Name',
                    description: 'Technician which will handle this ticket.'
                }
            ];

            CKEDITOR.addCss('span > .cke_placeholder { background-color: #ffeec2; }');

            CKEDITOR.replace('editor1', {
                plugins: 'autocomplete,textmatch,toolbar,wysiwygarea,basicstyles,link,undo,placeholder',
                toolbar: [{
                    name: 'document',
                    items: ['Undo', 'Redo']
                },
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic']
                    },
                    {
                        name: 'links',
                        items: ['Link', 'Unlink']
                    }
                ],
                on: {
                    instanceReady: function(evt) {
                        var itemTemplate = '<li data-id="{id}">' +
                                '<div><strong class="item-title">{title}</strong></div>' +
                                '<div><i>{description}</i></div>' +
                                '</li>',
                            outputTemplate = '{title}<span>&nbsp;</span>';

                        var autocomplete = new CKEDITOR.plugins.autocomplete(evt.editor, {
                            textTestCallback: textTestCallback,
                            dataCallback: dataCallback,
                            itemTemplate: itemTemplate,
                            outputTemplate: outputTemplate
                        });

                        // Override default getHtmlToInsert to enable rich content output.
                        autocomplete.getHtmlToInsert = function(item) {
                            return this.outputTemplate.output(item);
                        }
                    }
                }
            });

            function textTestCallback(range) {
                if (!range.collapsed) {
                    return null;
                }

                return CKEDITOR.plugins.textMatch.match(range, matchCallback);
            }

            function matchCallback(text, offset) {
                var pattern = /\{([A-z]|\})*$/,
                    match = text.slice(0, offset)
                        .match(pattern);
                if (!match) {
                    return null;
                }

                return {
                    start: match.index,
                    end: offset
                };
            }

            function dataCallback(matchInfo, callback) {
                var data = PLACEHOLDERS.filter(function(item) {
                    var itemName = '{' + item.name + '}';
                    return itemName.indexOf(matchInfo.query.toLowerCase()) == 0;
                });

                callback(data);
            }
            });
   /* var blockManager = editor.BlockManager;
    blockManager.add('some-block-id', {
            render: ({ el }) => {
            const btn = document.createElement('button');
    btn.innerHTML = 'Click me';
    btn.addEventListener('click', () => alert('Do something'))
    el.appendChild(btn);
    },
    });*/
    var pnm = editor.Panels;
    pnm.removeButton("options", "gjs-open-import-template");
    pnm.removeButton("options", "gjs-toggle-images");
    pnm.addButton('options', [{
        id: 'undo',
        className: 'fa fa-undo',
        attributes: {title: 'Undo'},
        command: function () { editor.runCommand('core:undo') }
    }, {
        id: 'redo',
        className: 'fa fa-repeat',
        attributes: {title: 'Redo'},
        command: function () { editor.runCommand('core:redo') }
    }
    ]);

    editor.Panels.removeButton("options", "import");
    pnm.addButton('options',
        [{
            id: 'save',
            className: 'btn-alert-button',
            label: 'Save and close',
            command: function (editor1, sender) {
                var newContent = ($('textarea#templateBuilder').val()).replace('||BODY||', editor.runCommand('gjs-get-inlined-html'));
                window.opener.mQuery('textarea.builder-html').val(newContent);
                window.close();
            },
            attributes: {title: 'Save and close'}
        }
        ]);
</script>