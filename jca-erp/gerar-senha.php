<?php
/**
 * Script para gerar hash de senha
 */

$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "Senha: $senha\n";
echo "Hash: $hash\n";
echo "\n";
echo "Use este hash no schema.sql\n";

