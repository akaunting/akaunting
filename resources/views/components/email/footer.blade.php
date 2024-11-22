<table border="0" style="width:100%; background-color: #F8F9FE; margin-top: 15px;">
    <tbody>
        <tr>
            <td>
                <table border="0" style="text-align: center; margin: 0 auto;">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" style="text-align: center; padding-top: 5px;">
                                <a href="{!! $url !!}" style="color: #676ba2; text-decoration: none;">{{ trans('footer.powered_by') }}&nbsp;</a>
                            </td>
                            <td align="center" valign="middle" style="text-align: center; padding-top: 5px;">
                                <a href="{!! $url !!}"><img src="{{ asset('public/img/akaunting-logo-wild-blue.png') }}" style="height:20px;" alt="Akaunting" /></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding: 5px 0 5px 0; color: #595959;">
                {!! trans('footer.tag_line', ['get_started_url' => $get_started]) !!}
            </td>
        </tr>
    </tbody>
</table>