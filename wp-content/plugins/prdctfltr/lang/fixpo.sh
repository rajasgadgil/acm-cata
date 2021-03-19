#!/bin/bash

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-en_UK.pot > prdctfltr-en_UK.pot_new;
mv prdctfltr-en_UK.pot_new prdctfltr-en_UK.pot;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-cz_CZ.po > prdctfltr-cz_CZ.po_new;
mv prdctfltr-cz_CZ.po_new prdctfltr-cz_CZ.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-de_DE.po > prdctfltr-de_DE.po_new;
mv prdctfltr-de_DE.po_new prdctfltr-de_DE.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-en_US.po > prdctfltr-en_US.po_new;
mv prdctfltr-en_US.po_new prdctfltr-en_US.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-es_ES.po > prdctfltr-es_ES.po_new;
mv prdctfltr-es_ES.po_new prdctfltr-es_ES.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-fr_FR.po > prdctfltr-fr_FR.po_new;
mv prdctfltr-fr_FR.po_new prdctfltr-fr_FR.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-it_IT.po > prdctfltr-it_IT.po_new;
mv prdctfltr-it_IT.po_new prdctfltr-it_IT.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-nl_NL.po > prdctfltr-nl_NL.po_new;
mv prdctfltr-nl_NL.po_new prdctfltr-nl_NL.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-ru_RU.po > prdctfltr-ru_RU.po_new;
mv prdctfltr-ru_RU.po_new prdctfltr-ru_RU.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-sk_SK.po > prdctfltr-sk_SK.po_new;
mv prdctfltr-sk_SK.po_new prdctfltr-sk_SK.po;

awk -v RS= -v re='(pf-settings\.php|pf-composer\.php|pf-widget\.php|update/)' '$0 ~ ("(^|\\n)#[^\\n]*" re) {sub(/\nmsgstr ".*/, "\nmsgstr \"\"")} {print $0"\n"}' prdctfltr-sr_RS.po > prdctfltr-sr_RS.po_new;
mv prdctfltr-sr_RS.po_new prdctfltr-sr_RS.po;
