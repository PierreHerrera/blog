<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'excerpt',
        'body',
        'user_id',
        'published',
        'image_path',
    ];

    protected function title():Attribute
    {
        return new Attribute(
            set: fn ($value) => strtolower($value), //Mutadores
            get: fn ($value) => ucfirst($value), //Accesores
        );
    }

    protected function image(): Attribute
    {
        return new Attribute(
            // get: fn () => $this->image_path ?? 'https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg'

            get: function(){
                if ( $this->image_path ) {
                    // return $this->image_path;
                    // Verificar si la url comienza con http:// o https://
                        if ( substr($this->image_path, 0, 8) === 'https://' ) {
                            return $this->image_path;
                        }
                            // Si es en s3, en cuanto a configuracion de s3 no muestra porque debe tener el dominio configurado desde DigitalOcean
                            // return Storage::disk('s3')->url($this->image_path);

                            // Disco por defecto
                            return Storage::url($this->image_path);

                            // Acceso al space de DigitalOcean sin limites
                            // return route('posts.image', $this);

                            // Acceso con limitacion de tiempo para el space de DigitalOcean
                            /* return Storage::temporaryUrl(
                                $this->image_path,
                                now()->addMinutes(5)
                            ); */
                } else {
                    return 'https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg';
                }
            }

            /* get: function(){
                if($this->image_path){

                    if (substr($this->image_path, 0, 8) === 'https://') {
                        return $this->image_path;
                    }

                    return Storage::url($this->image_path);

                    // return route('posts.image', $this);

                    // return Storage::temporaryUrl(
                    //     $this->image_path,
                    //     now()->addMinutes(5)
                    // );

                }else{
                    return 'https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg';
                }
            } */

        );
    }
    
    // Relacion de uno a uno inversa  -- Creo que aquí se equivocó
    public function category(){
        return $this->belongsTo(Category::class);
    }

    // Relacion de uno a muchos inversa
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Relacion uno a muchos polimorfica
    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    // Relacion de muchos a muchos polimorfica
    public function tags(){
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
