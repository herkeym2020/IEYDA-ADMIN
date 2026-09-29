<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admission Letter — Ilorin Emirate Youth Development Association</title>
<style>
  @page { size: A4; margin: 0; }
  * { box-sizing: border-box; }
  body {
    margin: 0;
    background: #e9eceb;
    font-family: "Segoe UI", Arial, sans-serif;
    color: #14201d;
  }

  .letter {
    width: 210mm;
    min-height: 297mm;
    margin: 0 auto;
    background: #fff;
    position: relative;
    overflow: hidden;
  }

  .top {
    height: 42mm;
    position: relative;
    background: #fff;
    overflow: hidden;
  }

  .top-green {
    position: absolute;
    inset: 0 auto 0 0;
    width: 51%;
    background: #063d32;
    clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);
  }

  .top-gold {
    position: absolute;
    top: 0;
    left: 47%;
    width: 12mm;
    height: 100%;
    background: #c99b3b;
    transform: skewX(-33deg);
    transform-origin: top;
  }

  .brand {
    position: absolute;
    left: 12mm;
    top: 5mm;
    display: flex;
    align-items: center;
    color: white;
  }

  .logo-img {
    width: 24mm;
    height: 24mm;
    object-fit: contain;
    margin-right: 4mm;
    background: white;
    border-radius: 50%;
    padding: 1mm;
    border: 2px solid #d5ad50;
  }

  .brand-name {
    font-size: 13px;
    line-height: 1.1;
    font-weight: 800;
    letter-spacing: .5px;
    max-width: 50mm;
  }

  .motto {
    margin-top: 3px;
    font-size: 7px;
    color: #e0b956;
    font-weight: 700;
    letter-spacing: 1px;
  }

  .heading {
    position: absolute;
    right: 12mm;
    top: 10mm;
    width: 85mm;
    text-align: center;
  }

  .heading h1 {
    margin: 0;
    color: #083e33;
    font-size: 20px;
    letter-spacing: 1px;
    font-weight: 800;
  }

  .heading .line {
    height: 1px;
    background: #c99b3b;
    margin: 6px 0;
    position: relative;
  }

  .heading .line:after {
    content: "";
    width: 6px;
    height: 6px;
    background: #c99b3b;
    position: absolute;
    left: 50%;
    top: -3px;
    transform: rotate(45deg);
  }

  .heading p {
    margin: 0;
    font-size: 8px;
    font-weight: 600;
    color: #4d5956;
  }

  .meta {
    text-align: right;
    margin: 3mm 12mm 0 auto;
    width: 65mm;
    border-right: 2px solid #c99b3b;
    padding-right: 3mm;
    font-size: 8px;
    line-height: 1.6;
  }

  .content {
    padding: 0 15mm 20mm;
    position: relative;
    min-height: 230mm;
  }

  .watermark {
    position: absolute;
    right: 6mm;
    top: 20mm;
    width: 90mm;
    height: 90mm;
    opacity: .03;
    border: 10px solid #174e42;
    border-radius: 50%;
    transform: rotate(-10deg);
  }

  .watermark:before {
    content: "IEYDA";
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    font-size: 70px;
    color: #174e42;
  }

  .recipient {
    margin-top: 4mm;
    line-height: 1.5;
    font-size: 9px;
    position: relative;
    z-index: 2;
  }

  .recipient strong { font-size: 10px; }

  .salutation {
    margin-top: 5mm;
    font-size: 9px;
    position: relative;
    z-index: 2;
  }

  .congrats {
    margin: 5mm 0 4mm;
    color: #bd9134;
    font-family: Georgia, serif;
    font-size: 24px;
    font-style: italic;
    border-bottom: 2px solid #bd9134;
    width: fit-content;
    padding-bottom: 2px;
    position: relative;
    z-index: 2;
  }

  .subtitle {
    font-size: 10px;
    font-weight: 800;
    color: #083e33;
    letter-spacing: .8px;
    margin: -1mm 0 5mm;
    position: relative;
    z-index: 2;
  }

  .body-text {
    position: relative;
    z-index: 2;
    font-size: 9.5px;
    line-height: 1.6;
    max-width: 174mm;
  }

  .body-text p { margin: 0 0 4mm; }

  .highlight {
    display: inline-block;
    background: #0a493b;
    color: white;
    padding: 1px 7px;
    font-weight: 800;
    letter-spacing: .4px;
  }

  .closing {
    margin-top: 4mm;
  }

  .signature {
    width: 35mm;
    height: 12mm;
    border-bottom: 2px solid #222;
    margin: 2mm 0 1mm;
    position: relative;
  }

  .signature:after {
    content: "A. Yusuff";
    position: absolute;
    left: 3mm;
    bottom: 2mm;
    font-family: cursive;
    font-size: 15px;
    transform: rotate(-4deg);
  }

  .official {
    font-size: 8.5px;
    line-height: 1.4;
  }

  .official strong {
    font-size: 10px;
    color: #073f34;
  }

  .seal {
    position: absolute;
    right: 15mm;
    bottom: 8mm;
    width: 26mm;
    height: 26mm;
    border-radius: 50%;
    background: #d1a13e;
    border: 4px double #7b5c18;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
    display: grid;
    place-items: center;
    color: #073f34;
    font-weight: 900;
    text-align: center;
    font-size: 8px;
  }

  .seal:after {
    content: "IEYDA";
    position: absolute;
    bottom: 5px;
    font-size: 6px;
    letter-spacing: 1px;
  }

  .footer {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 20mm;
    background: #063d32;
    border-top: 2px solid #c99b3b;
    color: white;
    display: flex;
    align-items: center;
    padding: 0 10mm;
    gap: 6mm;
    font-size: 6.5px;
  }

  .footer-item {
    flex: 1;
    line-height: 1.5;
  }

  .footer-item strong {
    display: block;
    color: #e0b956;
    font-size: 7px;
    margin-bottom: 1px;
  }
</style>
</head>
<body>

<main class="letter">

  <header class="top">
    <div class="top-green"></div>
    <div class="top-gold"></div>

    <div class="brand">
      <img class="logo-img" src="{{ public_path('ieyda_logo.png') }}" alt="IEYDA Logo" />
      <div>
        <div class="brand-name">ILORIN EMIRATE<br>YOUTH DEVELOPMENT<br>ASSOCIATION</div>
        <div class="motto">YOUTH • EDUCATION • DEVELOPMENT</div>
      </div>
    </div>

    <div class="heading">
      <h1>ADMISSION LETTER</h1>
      <div class="line"></div>
      <p>Free ICT & Vocational Skills Acquisition Programme</p>
    </div>
  </header>

  <div class="meta">
    <div><strong>Ref No.:</strong> {{ $admissionNumber }}</div>
    <div><strong>Date:</strong> {{ now()->format('jS F, Y') }}</div>
    <div><strong>Programme:</strong> ICT Training Programme</div>
  </div>

  <section class="content">
    <div class="watermark"></div>

    <div class="recipient">
      <strong>{{ $applicant->full_name }}</strong><br>
      {{ $applicant->address }}<br>
      {{ $applicant->lga ? $applicant->lga . ', ' : '' }}{{ $applicant->state }} State.
    </div>

    <div class="salutation">Dear {{ $applicant->full_name }},</div>

    <div class="congrats">Congratulations!</div>
    <div class="subtitle">IEYDA FREE ICT & VOCATIONAL SKILLS ACQUISITION PROGRAMME</div>

    <div class="body-text">
      <p>
        We are pleased to inform you that your application to participate in the
        <strong>IEYDA 11th Free ICT & Vocational Skills Acquisition Programme</strong>
        has been carefully reviewed and approved.
      </p>

      <p>
        You have been admitted as a
        <span class="highlight">REGISTERED PARTICIPANT</span>
        in the programme.
      </p>

      <p>
        Your admission is valid for the <strong>{{ $courses }}</strong>
        @if($vocational) course with vocational sessions in <strong>{{ $vocational }}</strong>@endif,
        and training commences <strong>Monday, August 3, 2026</strong> at the
        <strong>IEYDA National Secretariat, Aishat Adepate House, 46 Edun Street, Ilorin</strong>.
      </p>

      <p>
        Please note that attendance is mandatory for all sessions. Punctuality and dedication are key to
        successfully completing this programme. Upon successful completion, you will be awarded a
        certificate of participation.
      </p>

      <p>We look forward to your active participation in the programme as we work together for the development and advancement of youth across the Ilorin Emirate. We warmly welcome you and wish you a successful and rewarding learning experience!</p>

      <div class="closing">
        Yours faithfully,

        <div class="signature"></div>

        <div class="official">
          <strong>NATIONAL PRESIDENT</strong><br>
          Ilorin Emirate Youth Development Association
        </div>
      </div>
    </div>

    <div class="seal">IEYDA<br>ICT PROGRAMME</div>
  </section>

  <footer class="footer">
    <div class="footer-item">
      <strong>OFFICE</strong>
      Aishat Adepate House, 46 Edun Street,<br>
      Ilorin, Kwara State, Nigeria.
    </div>
    <div class="footer-item">
      <strong>PHONE</strong>
      0703 673 9943<br>
      0815 151 5608
    </div>
    <div class="footer-item">
      <strong>EMAIL</strong>
      info@ilorinemirateyouths.com<br>
      admin@ilorinemirateyouths.com
    </div>
    <div class="footer-item">
      <strong>WEB</strong>
      www.ilorinemirateyouths.com
    </div>
  </footer>

</main>
</body>
</html>