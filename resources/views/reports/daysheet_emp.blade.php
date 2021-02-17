<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <table cellspacing="0" cellpadding="0">
    <tr>
        <th style="width: 335px">&nbsp;</th>
        <th style="width: 335px; font-size:25px; padding:20px">Employee Wise Day Sheet</th>
        <th style="width: 335px">&nbsp;</th>
    </tr>
    <tr>
        <th>Date Range {{ $from }} to {{ $to }}</th>
        <th style="width: 335px; font-size:25px; padding:20px"></th>
        <th style="width: 335px">&nbsp;</th>
    </tr>
    
    </table>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <th style=" border: 1px solid black;width: 190px">Employee</th>
            @for ($i=1; $i < 32 ; $i++ )
                <th style=" border: 1px solid black;width: 25px">{{ $i  }}</th>
            @endfor
            <th style=" border: 1px solid black;width: 25px">Day</th>
            <th style=" border: 1px solid black;width: 25px">Night</th>
        </tr>

            <?php $default="default"; ?>

            @foreach($data as $value)
            <?php
            $day=0;
            $night=0;
            $d="";
            $n="";
            
            if($default=="default" || $default!= $value->employee_id){
            
            ?>
        <tr>
            <th style=" border-top: 1px solid black; border-left: 1px solid black;width: 190px" >{{ $value->name }}</th>
            @for ($i=1; $i < 32 ; $i++ )

                <?php $id = $value->$i;
                if($id == '0'){
                    $id='D';
                    $day++;
                }elseif($id == '1'){
                    $id='N';
                    $night++;
                }
                ?>
                <th style=" border: 1px solid black;width: 25px">{{  $id  }}</th>
            
            @endfor
                <th style="border: 1px solid black;width: 25px">{{ $day }}</th>
                <th style="border: 1px solid black;width: 25px">{{ $night }}</th>
        </tr>  border: 1px solid black;width: 25px"
        <?php }else{ ?>

            <tr>
            <th style="border-left: 1px solid black;width: 190px"></th>
            @for ($i=1; $i < 32 ; $i++ )

                <?php $id = $value->$i;
                if($id == '0'){
                    $id='D';
                    $day++;
                }elseif($id == '1'){
                    $id='N';
                    $night++;
                }
                ?>
                <th style=" border: 1px solid black;width: 25px">{{  $id  }}</th>
            
            @endfor
            <th style="border: 1px solid black;width: 25px">{{ $day }}</th>
            <th style="border: 1px solid black;width: 25px">{{ $night }}</th>
        </tr>

            <?php } ?>

        <?php  $default= $value->employee_id; ?>
        @endforeach

        <tr>
            <th style=" border-bottom: 1px solid black;width: 190px"></th>
            @for ($i=1; $i < 32 ; $i++ )
            <th style=" border-bottom: 1px solid black;width: 25px"></th>
            @endfor
            <th style=" border-bottom: 1px solid black;width: 25px"></th>
            <th style=" border-bottom: 1px solid black;width: 25px"></th>
        </tr>
    </table>
</body>
</html>