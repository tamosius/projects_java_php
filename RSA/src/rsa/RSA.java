/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package rsa;

import java.math.BigInteger;
import java.util.Random;

/**
 *
 * @author Tomas
 */
public class RSA {

    private BigInteger p; // first prime number
    private BigInteger q; // second prime number
    private BigInteger n; // modulus (public key)
    private BigInteger phi;  // mathematical constant (totient)
    private BigInteger e;  // public key
    private BigInteger d;  // private key
    private int bitlength;  // specify a bitLength for the returned prime BigInteger, throw Arithmetic Exception if bitLength < 2

    private Random random;  // source of random bits used to select candidates to be tested for primality
    
    // no-args constructor
    public RSA() {

    }

    // generate prime numbers           
    public void generatePrimes(String bitlength) {

        this.random = new Random();

        this.bitlength = Integer.parseInt(bitlength);

        // 'probablePrime' function returns a positive BigInteger that is probably prime, with the specified bitLength.
        this.p = BigInteger.probablePrime(this.bitlength, this.random); // first prime number
        this.q = BigInteger.probablePrime(this.bitlength, this.random); // second prime number
    }

    // set first prime number from keyboard, if user decides to choose the prime by him self
    public void setFirstPrime(String firstPrime) {
        BigInteger firstPrimeNumber = new BigInteger(firstPrime);
        if (this.p == null || !(this.p.equals(firstPrimeNumber))) {
            this.p = firstPrimeNumber;
        }
    }

    // set second prime number from keyboard, if user decides to choose the prime by him self
    public void setSecondPrime(String secondPrime) {
        BigInteger secondPrimeNumber = new BigInteger(secondPrime);
        if (this.q == null || !(this.q.equals(secondPrimeNumber))) {
            this.q = secondPrimeNumber;
        }
    }

    // get first prime number generated
    public String getFirstPrime() {
        return this.p.toString();
    }

    // get second prime number generated
    public String getSecondPrime() {
        return this.q.toString();
    }

    // calculate modulus (first public key)
    public String getModulus() {

        this.n = this.p.multiply(this.q);
        return this.n.toString();
    }

    // calculate mathematical constant (phi) - totient
    public String getPHI() {

        this.phi = this.p.subtract(BigInteger.ONE).multiply(this.q.subtract(BigInteger.ONE));
        return this.phi.toString();
    }

    // get second public key 'e', this key must not share a factor with 'phi'
    public String getSecondPublicKey() {
        this.e = BigInteger.probablePrime(this.bitlength / 2, this.random); // we start searching from 3

        // 'gcd()' function finds greatest common divisor between two prime numbers.
        // in this case the greatest common divisor must be equal to 1.
        while (!(this.e.gcd(this.phi).equals(BigInteger.ONE))) {

            // This method returns the first integer greater than this BigInteger that is probably prime.
            this.e = this.e.nextProbablePrime();
        }

        return this.e.toString();
    }

    // get private key for decrypting message
    public String getPrivateKey() {

        // This method returns a BigInteger whose value is (this-1 mod phi).
        this.d = this.e.modInverse(this.phi);
        return this.d.toString();
    }

    // method to check if the number passed in, is a prime number and returns true of false
    public boolean isPrime(String number) {
        BigInteger checkNumber = new BigInteger(number);

        int certainty = 50;
        // 'isProbablePrime()' checks if the BigInteger value provided is a prime number,
        // The certainty argument plays a big role in the consistency of the check if the number is prime,
        // the higher the certainty the closer it will be that this BigInteger is a prime however the execution time is much higher.

        return checkNumber.isProbablePrime(certainty);
    }

    
    // encrypt the plain text message
    public String encryptMessage(String message, String publicN, String publicE) {
        
        // convert received message to bytes array
        byte[] messageInBytes = message.getBytes(); 
        // convert first public key to BigInteger value
        BigInteger publicKeyN = new BigInteger(publicN);
        // convert second public key to BigInteger value
        BigInteger publicKeyE = new BigInteger(publicE);
        
        // return enctypted message
        return new BigInteger(messageInBytes).modPow(publicKeyE, publicKeyN).toString();
    }
    // decrypt cipher text message
    public String decryptMessage(String message, String publicN, String privateD) {
        
        // convert encrypted message to BigInteger value
        BigInteger encryptedMessage = new BigInteger(message);
        // convert public key to BigInteger value
        BigInteger publicKeyN = new BigInteger(publicN);
        // convert private key to BigInteger value
        BigInteger privateKeyD =  new BigInteger(privateD);
        
        // 'modPow' method raises the BigInteger by the power of d and gets modulus n, converts to byte array and returns as a String
        return new String((encryptedMessage.modPow(privateKeyD, publicKeyN)).toByteArray());
    }
    
}
