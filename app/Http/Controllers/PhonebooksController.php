<?php

namespace App\Http\Controllers;

use App\Phonebook;
use Illuminate\Http\Request;

class PhonebooksController extends Controller
{

    public function index($term = null){
        if ($term != null) {
            $phonebook = Phonebook::where('name', 'like', '%'.$term.'%')->get();
            return response()->json($phonebook, 200);
        }

        return $this->_allRecord();
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:6',
            'email' => 'required|email|unique:phonebooks',
            'phone' => 'required|min:7'
        ]);

        Phonebook::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        return $this->_allRecord();
    }


    public function edit(Phonebook $phonebook){
        return response()->json($phonebook, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Phonebook  $phonebook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Phonebook $phonebook)
    {
        $request->validate([
            'name' => 'required|min:6',
            'phone' => 'required|min:7'
        ]);

        $phonebook->update([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        return $this->_allRecord();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Phonebook  $phonebook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Phonebook $phonebook)
    {
        if ($phonebook->delete()) {
            return $this->_allRecord();
        } else {
            return response()->json(425, ['delete' => 'Error deleting record']);
        }
    }

    private function _allRecord()
    {
        $phonebook = Phonebook::latest()->paginate(5);
        return response()->json($phonebook, 200);
    }
}
