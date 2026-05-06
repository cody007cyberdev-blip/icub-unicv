<?php
// garantir que só os metodos POST serão realizados aqui
if ($_SERVER['REQUEST_METHOD'] == 'POST'):
    
// para obter as informacoes do perfil do candidato
$perfil = Database::prepare('SELECT * FROM tbl_candidato WHERE id = ?', [
    $_SESSION['usuario']['id']
])->fetch();


    /**
     ** Codigo para atualizar a foto de perfil
     */
    if (isset($_FILES['foto'])) {
        $data_ = new DateTime();
        $nomeficheiro_foto = $data_->getTimestamp() . '_' . $_FILES['foto']['name'];
        $tmp_foto = $_FILES["foto"]['tmp_name'];
        // valida a foto, se foi upload
        if ($tmp_foto != '') {
            // update para a pasta e verifica ser foi updated
            if (move_uploaded_file($tmp_foto, PATH_UPLOADS . "/fotos_cand/$nomeficheiro_foto")) {
                // elimina old foto
                $old_foto = PATH_UPLOADS . "/fotos_cand/" . $perfil['foto'];
                if ($perfil['foto'] != '' && file_exists($old_foto)) {
                    unlink($old_foto);
                }
                // adiciona a nova foto na base de dados
                $model = new Model('tbl_candidato');
                $model->update($perfil['id'], [
                    'foto' => $nomeficheiro_foto
                ]);
                // troca a foto na session
                $_SESSION['usuario']['foto'] = $nomeficheiro_foto;
                flashMessage('Sua Foto de Perfil foi Atualizado');
                flashStatus('success');
            }
        } else {
            flashMessage('Tente novamente, parece nenhuma foto foi selecionada');
            flashStatus('info');

        }
        // redireciona para a mesma pagina (post redirect get)
        redirect();
    }


    /**
     * Codigo para alterar password do utilizador
     */
    if (isset($_POST['submitPassword'])) {
        $id = $perfil['id'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        if ($newPassword == $confirmPassword) {
            // palavrapasse confirmado
            if (password_verify($currentPassword, $perfil['password'])) {
                // palavra passe antiga OK
                $password = password_hash($newPassword, PASSWORD_DEFAULT);
                $atualizado = (new Model('tbl_candidato'))->update($id, ['password' => $password]);
                if ($atualizado) {
                    flashMessage("Palavra-passe Alterado com sucesso!", 'success');
                }
            } else {
                flashMessage("Palavra-passe actual Incorreta!", 'warning');
            }
        }
        // post redirect get para mesma pagina
        redirect();
    }


    /**
     * Codigo para atualizar a dados pessoais
     */
    if (isset($_POST['submitPersonal'])) {
        // sanetizar as entradas para previnir sql Injection
        $unome = htmlspecialchars($_POST['nome']);
        $usexo = htmlspecialchars($_POST['sexo']);
        $ucontacto = htmlspecialchars($_POST['contacto']);
        $uendereco = htmlspecialchars($_POST['endereco']);
        $udatNasc = htmlspecialchars($_POST['dataNasc']);
        $nacionalidade = htmlspecialchars($_POST['nacionalidade']);

        $ok = (new Model('tbl_candidato'))->update($perfil['id'], [
            'nome' => $unome,
            'sexo' => $usexo,
            'contacto' => $ucontacto,
            'endereco' => $uendereco,
            'nacionalidade' => $nacionalidade
        ]);

        if ($ok) {
            flashMessage('Seus Dados foi Atualizado com sucesso');
            flashStatus('success');
            $_SESSION['usuario']['nome'] = $unome;
        } else {
            flashMessage('Erro ao Atualizar dados');
            flashStatus('error');
        }


        // redireciona para a mesma pagina (post redirect get)
        redirect();
    }




endif;
// FIM //! Não faca nenhum codigo abaixo dessa linha