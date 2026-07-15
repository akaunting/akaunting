<?php

return [

    'company' => [
        'description'                   => 'Cambia il nome dell\'azienda, l\'email, la partita IVA, ecc.',
        'search_keywords'               => 'azienda, nome, email, telefono, indirizzo, paese, partita IVA, logo, città, paese, stato, provincia, codice postale',
        'name'                          => 'Nome',
        'email'                         => 'Email',
        'phone'                         => 'Telefono',
        'address'                       => 'Indirizzo',
        'edit_your_business_address'    => 'Modifica l\'indirizzo della tua azienda',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Queste informazioni sono visibili nei record creati.',
            'address'                   => 'L\'indirizzo verrà utilizzato nelle fatture, fatture di acquisto e altri documenti emessi.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Imposta anno fiscale, fuso orario, formato data e altre localizzazioni',
        'search_keywords'               => 'finanziario, anno, inizio, denota, ora, zona, data, formato, separatore, sconto, percentuale',
        'financial_start'               => 'Inizio anno finanziario',
        'timezone'                      => 'Fuso orario',
        'financial_denote' => [
            'title'                     => 'Denotazione anno finanziario',
            'begins'                    => 'In base all\'anno in cui inizia',
            'ends'                      => 'In base all\'anno in cui termina',
        ],
        'preferred_date'                => 'Data preferita',
        'date' => [
            'format'                    => 'Formato data',
            'separator'                 => 'Separatore di data',
            'dash'                      => 'Trattino (-)',
            'dot'                       => 'Punto (.)',
            'comma'                     => 'Virgola (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Spazio ( )',
        ],
        'percent' => [
            'title'                     => 'Posizione percentuale (%)',
            'before'                    => 'Prima del numero',
            'after'                     => 'Dopo il numero',
        ],
        'discount_location' => [
            'name'                      => 'Posizione dello sconto',
            'item'                      => 'Alla riga',
            'total'                     => 'Al totale',
            'both'                      => 'Sia alla riga che al totale',
        ],

        'form_description' => [
            'fiscal'                    => 'Imposta il periodo dell\'anno finanziario utilizzato dalla tua azienda per la tassazione e la rendicontazione.',
            'date'                      => 'Seleziona il formato della data che desideri vedere ovunque nell\'interfaccia.',
            'other'                     => 'Seleziona dove viene visualizzato il segno di percentuale per le imposte. È possibile abilitare sconti sulle voci e sul totale per fatture e fatture di acquisto.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizza prefisso fattura, numero, termini, piè di pagina, ecc.',
        'search_keywords'               => 'personalizzare, fattura, numero, prefisso, cifra, successivo, logo, nome, prezzo, quantità, modello, titolo, sottotitolo, piè di pagina, nota, nascondi, scadenza, colore, pagamento, termini, colonna',
        'prefix'                        => 'Prefisso del numero',
        'digit'                         => 'Cifre del numero',
        'next'                          => 'Prossimo numero',
        'logo'                          => 'Logo',
        'custom'                        => 'Personalizzato',
        'item_name'                     => 'Nome articolo',
        'item'                          => 'Articoli',
        'product'                       => 'Prodotti',
        'service'                       => 'Servizi',
        'price_name'                    => 'Nome del prezzo',
        'price'                         => 'Prezzo',
        'rate'                          => 'Aliquota',
        'quantity_name'                 => 'Nome quantità',
        'quantity'                      => 'Quantità',
        'payment_terms'                 => 'Termini di pagamento',
        'title'                         => 'Titolo',
        'subheading'                    => 'Sottotitolo',
        'due_receipt'                   => 'Alla ricezione',
        'due_days'                      => 'Scade entro :days giorni',
        'due_custom'                    => 'Giorno/i personalizzato/i',
        'due_custom_day'                => 'dopo il giorno',
        'choose_template'               => 'Scegli modello di fattura',
        'default'                       => 'Predefinito',
        'classic'                       => 'Classico',
        'modern'                        => 'Moderno',
        'logo_size_width'               => 'Larghezza logo',
        'logo_size_height'              => 'Altezza logo',
        'hide' => [
            'item_name'                 => 'Nascondi nome articolo',
            'item_description'          => 'Nascondi descrizione articolo',
            'quantity'                  => 'Nascondi quantità',
            'price'                     => 'Nascondi prezzo',
            'amount'                    => 'Nascondi importo',
        ],
        'column'                        => 'Colonna|Colonne',

        'form_description' => [
            'general'                   => 'Imposta le impostazioni predefinite per la formattazione dei numeri delle fatture e dei termini di pagamento.',
            'template'                  => 'Seleziona uno dei modelli seguenti per le tue fatture.',
            'default'                   => 'La selezione delle impostazioni predefinite per le fatture precompilerà titoli, sottotitoli, note e piè di pagina. Così non avrai bisogno di modificare le fatture ogni volta per avere un aspetto più professionale.',
            'column'                    => 'Personalizza il nome delle colonne della fattura. Se desideri nascondere le descrizioni degli articoli e gli importi nelle righe, puoi modificarlo qui.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Scegli il modello di trasferimento',
        'second'                        => 'Secondo',
        'third'                         => 'Terzo',
    ],

    'default' => [
        'description'                   => 'Conto, valuta e lingua predefiniti della tua azienda',
        'search_keywords'               => 'conto, valuta, lingua, imposta, pagamento, metodo, impaginazione',
        'list_limit'                    => 'Record per pagina',
        'use_gravatar'                  => 'Usa Gravatar',
        'income_category'               => 'Categoria entrata',
        'expense_category'              => 'Categoria uscita',
        'address_format'                => 'Formato indirizzo',
        'address_tags'                  => '<strong>Tag disponibili:</strong> :tags',

        'form_description' => [
            'general'                   => 'Seleziona il conto, le imposte e il metodo di pagamento predefiniti per creare rapidamente i record. Dashboard e report vengono visualizzati nella valuta predefinita.',
            'category'                  => 'Seleziona le categorie predefinite per accelerare la creazione dei record.',
            'other'                     => 'Personalizza le impostazioni predefinite della lingua aziendale e il funzionamento dell\'impaginazione.',
        ],
    ],

    'email' => [
        'description'                   => 'Modifica il protocollo di invio e i modelli email',
        'search_keywords'               => 'email, invio, protocollo, smtp, host, password',
        'protocol'                      => 'Protocollo',
        'php'                           => 'Mail PHP',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Host SMTP',
            'port'                      => 'Porta SMTP',
            'username'                  => 'Nome utente SMTP',
            'password'                  => 'Password SMTP',
            'encryption'                => 'Protezione SMTP',
            'none'                      => 'Nessuno',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Percorso Sendmail',
        'log'                           => 'Registra le email',
        'email_service'                 => 'Servizio di posta elettronica',
        'email_templates'               => 'Modelli di posta elettronica',

        'form_description' => [
            'general'                   => 'Invia email regolari al tuo team e ai tuoi contatti. È possibile configurare il protocollo e le impostazioni SMTP.',
        ],

        'templates' => [
            'description'               => 'Modifica i modelli di posta elettronica',
            'search_keywords'           => 'email, modello, oggetto, corpo, tag',
            'subject'                   => 'Oggetto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tag disponibili:</strong> :tag_list',
            'invoice_new_customer'      => 'Modello nuova fattura (inviato al cliente)',
            'invoice_remind_customer'   => 'Modello promemoria fattura (inviato al cliente)',
            'invoice_remind_admin'      => 'Modello promemoria fattura (inviato all\'amministratore)',
            'invoice_recur_customer'    => 'Modello fattura ricorrente (inviato al cliente)',
            'invoice_recur_admin'       => 'Modello fattura ricorrente (inviato all\'amministratore)',
            'invoice_view_admin'        => 'Modello visualizzazione fattura (inviato all\'amministratore)',
            'invoice_payment_customer'  => 'Modello ricevuta di pagamento (inviato al cliente)',
            'invoice_payment_admin'     => 'Modello pagamento ricevuto (inviato all\'amministratore)',
            'bill_remind_admin'         => 'Modello promemoria fattura di acquisto (inviato all\'amministratore)',
            'bill_recur_admin'          => 'Modello fattura di acquisto ricorrente (inviato all\'amministratore)',
            'payment_received_customer' => 'Modello ricevuta di pagamento (inviato al cliente)',
            'payment_made_vendor'       => 'Modello pagamento effettuato (inviato al fornitore)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Pianificazione',
        'description'                   => 'Promemoria automatici e comando per le ricorrenze',
        'search_keywords'               => 'automatico, promemoria, ricorrente, cron, comando',
        'send_invoice'                  => 'Invia promemoria fattura',
        'invoice_days'                  => 'Invia dopo i giorni di scadenza',
        'send_bill'                     => 'Invia promemoria fattura di acquisto',
        'bill_days'                     => 'Invia prima dei giorni di scadenza',
        'cron_command'                  => 'Comando cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Ora di esecuzione',

        'form_description' => [
            'invoice'                   => 'Abilita o disabilita e imposta promemoria per le tue fatture quando sono scadute.',
            'bill'                      => 'Abilita o disabilita e imposta promemoria per le fatture di acquisto prima che scadano.',
            'cron'                      => 'Copia il comando cron che il tuo server dovrebbe eseguire. Imposta l\'ora per attivare l\'evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorie illimitate per entrata, uscita e articolo',
        'search_keywords'               => 'categoria, entrata, uscita, articolo',
    ],

    'currencies' => [
        'description'                   => 'Crea e gestisci le valute e imposta i loro tassi',
        'search_keywords'               => 'predefinito, valuta, valute, codice, tasso, simbolo, precisione, posizione, decimale, migliaia, contrassegno, separatore',
    ],

    'taxes' => [
        'description'                   => 'Aliquote fiscali fisse, normali, inclusive e composte',
        'search_keywords'               => 'imposta, aliquota, tipo, fisso, inclusivo, composto, ritenuta',
    ],

];
