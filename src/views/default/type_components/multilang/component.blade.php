<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='control-label col-sm-2'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}} input_fields_wrap {{$name}}">

        <div class="input-group">
            <div class="input-group-addon">en</div>
            <input type='text' title="{{$form['label']}}"
                   {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=".$validation['max']:""}} class='form-control {{$name}} first_value'
                   name="{{$name}}[]" id="{{$name}}" value='{{$value}}'/> <span class="input-group-addon" style="padding: 1px;"><button
                        class="add_field_button {{$name}}  btn btn-danger  btn-xs"><i class='fa fa-plus'></i></button></span>
            <input type="hidden" name="{{$name}}__lang[]" value="en" />
        </div>

        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>

    </div>

    @push('bottom')
        <script>
            $(document).ready(function () {
                var max_fields_{{$name}}    = "{{ @$form['max_fields'] }}";
                var max_fields_{{$name}}    = parseInt(max_fields_{{$name}}) ? max_fields_{{$name}} : 5; //maximum input boxes allowed
                var wrapper_{{$name}}       = $(".input_fields_wrap").filter(".{{$name}}"); //Fields wrapper
                var add_button_{{$name}}    = $(".add_field_button").filter(".{{$name}}"); //Add button ID


                var count_{{$name}} = 1; //initial text box count
                $(add_button_{{$name}}).click(function (e) { //on add input button click
                    e.preventDefault();

                    swal({
                        title: "Language code",
                        text: "Please select a language:",
                        type: "input",
                        showCancelButton: true,
                        closeOnConfirm: true,
                        inputPlaceholder: "Input a country code (2 lower char)"
                        }, function (inputValue) {

                            if (inputValue != false && count_{{$name}} < max_fields_{{$name}} ) { //max input box allowed

                                var country_code = inputValue; // TODO

                                count_{{$name}}++; //text box increment

                                $(wrapper_{{$name}}).append('<div>\n' +
                                    '<input type="hidden" name="{{$name}}__lang[]" value="' + country_code + '" />\n' +
                                    '<div class="input-group">\n' +
                                    '<div class="input-group-addon">' + country_code + '</div>\n' +
                                    '<input class="form-control" {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=".$validation['max']:""}} type="text" name="{{$name}}[]"/>\n' +
                                    '</div>\n' +
                                    '<a href="#" class="remove_field {{$name}}"><i class="fa fa-minus"></a></div>'
                                ); //add input box
                            }
                    });

                });

                $(wrapper_{{$name}}).on("click", ".remove_field ", function (e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    count_{{$name}}--;
                })

                function Load() {
                    var value = '{{$value}}';
                    if (value != '') {
                        var data = html_decode(value);
                        try {
                            var strings = $.parseJSON(data);
                            var i = 0;
                            $.each(strings, function(code, string) {
                                if (i == 0) {
                                    $(".first_value").filter(".{{$name}}").val(string); // 0 = en
                                } else {
                                    // <div class="input-group-addon">$</div>
                                    $(wrapper_{{$name}}).append('<div>\n' +
                                        '<input type="hidden" name="{{$name}}__lang[]" value="' + code + '" />\n' +
                                        '<div class="input-group">\n' +
                                        '<div class="input-group-addon">' + code + '</div>\n' +
                                        '<input class="form-control" {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=".$validation['max']:""}}  type="text" name="{{$name}}[]" value="' + string + '"/>\n' +
                                        '</div>\n' +
                                        '<a href="#" class="remove_field {{$name}}"><i class="fa fa-minus"></a></div>'
                                    ); //add input box
                                }
                                i++;
                            });
                        } catch(e) {
                        }
                    }

                }

                function html_decode(str) {
                    var result = str
                    var elements = [
                        {'old': '&quot;', 'new': '"'},
                        {'old': '&#039;', 'new': "'"},
                    ]
                    $.each(elements, function(i, element) {
                        var regex = new RegExp(element['old'], 'g')
                        result = result.replace(regex, element['new']);
                    });
                    return result
                }

                Load();
            });
        </script>
    @endpush
</div>