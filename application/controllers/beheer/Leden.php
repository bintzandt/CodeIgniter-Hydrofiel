<?php

/**
 * Class Leden
 * Handles all beheer functionality related to Leden
 */
class Leden extends _BeheerController
{
	public Profile_model $profile_model;

	/**
     * Load Leden overzichts page
     */
    public function index()
    {
        $data['leden'] = $this->profile_model->get_profile();
        $this->loadView('beheer/leden/leden', $data);
    }

    /**
     * Load a form where a leden.csv file can be uploaded
     */
    public function importeren()
    {
        $this->loadView('beheer/leden/importeren');
    }

    /**
     * Delete a certain profile
     *
     * @param int|null $id
     */
    public function delete($id = null)
    {
        if ($id !== null) {
            if ($this->profile_model->delete($id)) {
                success('Gebruiker verwijderd.');
            } else {
                error('Het is niet gelukt om de gebruiker te verwijderen.');
            }
        }
        redirect('/beheer/leden');
    }


}
