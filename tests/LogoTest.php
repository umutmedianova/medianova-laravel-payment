<?php

namespace Medianova\LaravelPayment\Test;

use Medianova\LaravelPayment\Facades\Payment;

class LogoTest extends TestCase
{
    /**
     * Create Customer
     * @return void
     */
    public function testCustomerCreate()
    {
        $response = Payment::provider('logo')->customer([
            "FirmNr" => 999,
            "Kod" => "120.01.TEST223",
            "Unvan" => "B NAKLİYAT LTD. ŞTİ",
            "Adres" => "KAPTANPAŞA MAH. ZİYA TÜRKKAN SOK. NO=>1",
            "Semt" => "OKMEYDANI",
            "Ilce" => "ŞİŞLİ",
            "Il" => "İSTANBUL",
            "Ulke" => "TÜRKİYE",
            "Telefon1" => "02123201111",
            "Telefon2" => "",
            "FaksNo" => "02123201111",
            "Email" => "destek2@binport.com.tr",
            "VergiDairesi" => "ŞİŞLİ",
            "TCKimlik_Vergino" => "1111111116",
            "EIsKodu" => "1111111116",
            "EfaturaDurumu" => false
        ])->create();

        $res = (array)json_decode($response);
        $this->assertEquals(400, $res['code']);

    }

    /**
     * Create Invoice
     * @return void
     */
    public function testInvoiceCreate()
    {
        $response = Payment::provider('logo')->invoice([
            "FirmNr" => 999,
            "Numara" => "~",
            "FaturaTuru" => 9,
            "Tarih" => "2021-06-19T18=>03=>50.9924036+03=>00",
            "Saat" => "16=>00",
            "Isyeri" => 0,
            "Ambar" => 0,
            "OdemeSekli" => "",
            "Satirlar" => [
                [
                    "SatirTuru" => 4,
                    "UrunBilgisi" => [
                        "FirmNr" => 999,
                        "Kod" => "TEST_HİZMET",
                        "UrunAdi" => "TEST_HİZMET",
                        "Birim" => "ADET",
                        "SeriLotTakibi" => false,
                        "UrunYoksaKaydet" => false,
                        "UrunGuncelle" => false,
                        "EIsKodu" => ""
                    ],
                    "Miktar" => 1,
                    "KDV" => 180,
                    "Birim" => "ADET",
                    "Fiyat" => 1000,
                    "Indirimler" => [
                        [
                            "Oran" => 5,
                            "Tutar" => 50
                        ],
                        [
                            "Oran" => 0,
                            "Tutar" => 0
                        ]
                    ],
                    "SiparisId" => ""
                ],
                [
                    "SatirTuru" => 4,
                    "UrunBilgisi" => [
                        "FirmNr" => 999,
                        "Kod" => "TEST_HİZMET_2",
                        "UrunAdi" => "TEST_HİZMET_2",
                        "Birim" => "ADET",
                        "SeriLotTakibi" => false,
                        "UrunYoksaKaydet" => false,
                        "UrunGuncelle" => false,
                        "EIsKodu" => ""
                    ],
                    "Miktar" => 1,
                    "KDV" => 380,
                    "Birim" => "ADET",
                    "Fiyat" => 2000,
                    "Indirimler" => [
                        [
                            "Oran" => 5,
                            "Tutar" => 100
                        ],
                        [
                            "Oran" => 0,
                            "Tutar" => 0
                        ]
                    ],
                    "SiparisId" => ""
                ]
            ],
            "EIsKodu" => "",
            "GenelAciklama" => "",
            "Not2" => "",
            "Not3" => "",
            "Not4" => "",
            "VadeGunu" => 0,
            "SatisElemani" => ""
        ])->create();
        $res = (array)json_decode($response);
        $this->assertEquals(400, $res['code']);
    }

    /**
     * Get Transactions
     * @return void
     */
    public function testGetTransactions()
    {
        $response = Payment::provider('logo')->transactions(
            [
                "FirmNr" => 999,
                "DonemNr" => 1,
                "BaslangicTarihi" => "2021-01-01T16:43:49.2530818+03:00",
                "BitisTarihi" => "2021-06-26T16:43:49.2530818+03:00",
                "Kod" => [
                    "120.01.TEST10"
                ]
            ]
        )->get();
        $res = (array)json_decode($response);
        $this->assertEquals(200, $res['code']);
    }

}
