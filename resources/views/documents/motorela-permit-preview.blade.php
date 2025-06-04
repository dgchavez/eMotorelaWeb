<div class="preview-document" style="font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="font-size: 24px; font-weight: bold; margin-bottom: 5px;">MOTORELA OPERATOR'S PERMIT</div>
        <div style="font-size: 18px; margin-bottom: 20px;">Local Government Unit of Maramag</div>
        <div style="font-size: 14px; margin-bottom: 30px;">Permit No: {{ $permitNumber }}</div>
    </div>

    <div style="margin-bottom: 30px; line-height: 1.6;">
        <div style="margin-bottom: 20px;">
            <strong>OPERATOR INFORMATION</strong><br>
            Name: {{ $operator->full_name }}<br>
            Address: {{ $operator->address }}<br>
            TODA: {{ $operator->toda->name }}<br>
            Contact: {{ $operator->contact_number }}
        </div>

        <div style="margin-bottom: 20px;">
            <strong>VEHICLE INFORMATION</strong><br>
            @if($operator->motorcycles->isNotEmpty())
                <?php $motorcycle = $operator->motorcycles->first(); ?>
                MTOP No.: {{ $motorcycle->mtop_no }}<br>
                Motor No.: {{ $motorcycle->motor_no }}<br>
                Plate No.: {{ $motorcycle->plate_no }}<br>
                Make/Model: {{ $motorcycle->make }} ({{ $motorcycle->year_model }})
            @endif
        </div>

        <div style="margin-bottom: 20px;">
            <strong>AUTHORIZED DRIVERS</strong><br>
            1. {{ $operator->full_name }} (Primary)<br>
            @if($operator->alternate_driver)
                2. {{ $operator->alternate_driver }} (Alternate)
            @endif
        </div>

        <div style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
            <strong>VALIDITY</strong><br>
            Date Issued: {{ $issueDate->format('F d, Y') }}<br>
            Valid Until: {{ $validUntil->format('F d, Y') }}
        </div>
    </div>

    <div style="margin-top: 30px;">
        <p style="font-size: 12px; margin-bottom: 20px;">
            This permit authorizes the above-named operator to operate a Motorela within the Municipality of Maramag,
            subject to existing traffic rules and regulations, and the terms and conditions set forth by the Local
            Government Unit.
        </p>
    </div>

    <div style="margin-top: 50px; text-align: center;">
        <div style="margin-bottom: 40px;">
            <div style="width: 200px; border-top: 1px solid #000; margin: 10px auto;"></div>
            <div>Municipal Mayor</div>
            <div>Municipality of Maramag</div>
        </div>

        <div>
            <div style="width: 200px; border-top: 1px solid #000; margin: 10px auto;"></div>
            <div>TODA President</div>
            <div>{{ $operator->toda->name }}</div>
        </div>
    </div>

    <div style="position: absolute; bottom: 20px; right: 20px; font-size: 12px; color: #666;">
        PREVIEW ONLY - NOT FOR OFFICIAL USE
    </div>
</div>

<style>
.preview-document {
    background: white;
    padding: 40px;
    position: relative;
    min-height: 800px;
}

.preview-document::before {
    content: "PREVIEW";
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 100px;
    color: rgba(200, 200, 200, 0.3);
    pointer-events: none;
    z-index: 1000;
}
</style> 