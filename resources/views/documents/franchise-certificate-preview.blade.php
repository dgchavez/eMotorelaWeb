<div class="preview-document" style="font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="font-size: 24px; font-weight: bold; margin-bottom: 5px;">FRANCHISE CERTIFICATE</div>
        <div style="font-size: 18px; margin-bottom: 20px;">Local Government Unit of Maramag</div>
        <div style="font-size: 14px; margin-bottom: 30px;">Certificate No: {{ $certificateNumber }}</div>
    </div>

    <div style="margin-bottom: 30px; line-height: 1.6;">
        <p>This is to certify that:</p>
        
        <h2 style="text-align: center; margin: 20px 0;">{{ $operator->full_name }}</h2>
        
        <p>with residence at {{ $operator->address }}, is hereby granted a franchise to operate a Motorela
        under the {{ $operator->toda->name }} (TODA) in the Municipality of Maramag, subject to existing rules
        and regulations.</p>

        <div style="margin: 30px 0;">
            <strong>Vehicle Details:</strong><br>
            @if($operator->motorcycles->isNotEmpty())
                <?php $motorcycle = $operator->motorcycles->first(); ?>
                MTOP No: {{ $motorcycle->mtop_no }}<br>
                Motor No: {{ $motorcycle->motor_no }}<br>
                Plate No: {{ $motorcycle->plate_no }}<br>
                Make/Model: {{ $motorcycle->make }} ({{ $motorcycle->year_model }})
            @endif
        </div>

        <div style="margin-top: 20px; font-size: 14px; font-style: italic;">
            <p>Valid from: {{ $issueDate->format('F d, Y') }}<br>
            Valid until: {{ $validUntil->format('F d, Y') }}</p>
        </div>
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