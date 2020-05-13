# poster
test symfony app


```
jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')} && \
echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 && \
echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout


curl -X POST -H "Content-Type: application/json" http://localhost:8000/api/authentication_token -d '{"username":"root@example.com","password":"root@example.com"}'
```
