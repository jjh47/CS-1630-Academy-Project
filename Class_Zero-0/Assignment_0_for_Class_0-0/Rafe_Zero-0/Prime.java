import java.util.*;
public class Prime{
    public static void main (String args[]) {
		Scanner scan = new Scanner(System.in);
		System.out.print("Enter the starting number > ");
        int a = scan.nextInt();
		System.out.print("Enter the ending number > ");
        int b = scan.nextInt();
        int big = b;
        int small = a;
        if(a > b){
            big = a;
            small = b;
        }
        System.out.println("the prime numbers between " 
        + small + " and " + big + " is:");
        for (; small < big; small++) {
            int i = 2;
            for (; i <= small / i; i++) {
                if (small % i == 0) break;
            }
            if (i >= small / i ) System.out.println(small);
        }
    }
}